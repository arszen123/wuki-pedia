<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Manager\Article\ArticleManager;
use App\Manager\ManagerFactory;
use App\Models\Article;
use App\Models\ArticleDetailsHistory;
use App\Models\User;
use App\Repository\ArticleRepository;
use Carbon\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

/**
 * Article Backend controller
 * Class ArticleController
 * @package App\Http\Controllers
 */
class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user)
    {
        return view('admin.article.index', [
            'userArticles' => $user->articles,
            'userPendingArticles' => ArticleRepository::getUserPendingArticles($user),
        ]);
    }

    public function viewByHistoryId($historyId, User $user)
    {
        $adh = ArticleDetailsHistory::find($historyId);
        if ($adh === null) {
            return redirect(route('admin.article.list'));
        }
        /** @var ArticleManager $sm */
        $sm = ManagerFactory::getArticleManager($adh->article, $user);
        if ($sm->canViewHistory()) {
            return view('pub.article.show', [
                'article' => $adh->article,
                'participants' => $adh->article->getParticipants(),
                'articleDetails' => $adh,
                'availableLanguages' => []
            ]);
        }
        return redirect(route('admin.article.list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.article.form', [
            'languages' => Language::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return void
     * @throws \Throwable
     */
    public function store(Request $request, User $user)
    {
        $validator = $this->validateArticle($request, 'article.create');
        ArticleRepository::saveDetails($user, $validator->valid());
        return redirect(route('admin.article.list'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $language = Input::get('language');
        $article = Article::find($id);
        if ($article === null || $article->details->isEmpty()) {
            return redirect(route('admin.article.list'));
        }
        $state = $article->isPublished() ? ArticleDetailsHistory::STATE_APPROVED : ArticleDetailsHistory::STATE_PENDING;
        $articleDetails = $article->history()
            ->where('state', $state)
            ->orderByDesc('id')
            ->first();
        if ($language) {
            $articleDetails = $article->history()
                ->where('state', $state)
                ->where('lang_id', $language)
                ->orderByDesc('id')
                ->first();
            $articleDetails = $articleDetails ?? new ArticleDetailsHistory([
                    'lang_id' => $language
                ]);
        }

        return view('admin.article.edit', [
            'language' => $articleDetails->lang_id,
            'article' => $article,
            'articleDetails' => $articleDetails,
            'base_id' => ArticleRepository::getLastApprovedStateId($articleDetails),
            'languages' => Language::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function update(Request $request, User $user, $id)
    {
        $article = Article::findOrFail($id);
        if ($article === null || $article->details->isEmpty()) {
            return redirect(route('admin.article.list'));
        }
        $validator = $this->validateArticle($request, 'article.edit', [$id]);
        ArticleRepository::saveDetails($user, $validator->valid(), $article);
        return redirect(route('admin.article.list'));
    }

    /**
     * @param User $user
     * @param $articleDetailsHistoryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \App\Manager\Exception\NoManagerFoundForUser
     */
    public function editState(User $user, $articleDetailsHistoryId)
    {
        /** @var ArticleDetailsHistory $local */
        $local = ArticleDetailsHistory::find($articleDetailsHistoryId);
        $sm = ManagerFactory::getArticleManager($local->article, $user);
        if ($sm->canEditState($local)) {
            $adh = $sm->getMerged($local);
            return view('admin.article.state.edit', [
                'states' => ArticleDetailsHistory::AVAILABLE_STATES,
                'articleDetailsHistory' => $adh,
                'local' => $local,
                'base' => $local->base,
                'remote' => $local->article->details()
                    ->where('lang_id', $local->lang_id)
                    ->first(),
            ]);
        }
        return redirect(route('article.mod.requests', [$local->article->id]));
    }

    /**
     * @param Request $request
     * @param User $user
     * @param $articleDetailsHistoryId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \App\Manager\Exception\NoManagerFoundForUser
     */
    public function updateState(Request $request, User $user, $articleDetailsHistoryId)
    {
        $validator = $this->validateArticle($request, 'article.state.edit', [$articleDetailsHistoryId]);
        /** @var ArticleDetailsHistory $adh */
        $adh = ArticleDetailsHistory::find($articleDetailsHistoryId);
        $sm = ManagerFactory::getArticleManager($adh->article, $user);
        if ($sm->canEditState()) {
            $sm->setState($adh, Input::get('state'), $validator->valid());
        }
        return redirect(route('article.mod.requests', [$adh->article->id]));
    }

    public function listModRequests($id)
    {
        /** @var Article $article */
        $article = Article::find($id);
        $adh = $article
            ->history()
            ->where('state', ArticleDetailsHistory::STATE_PENDING)
            ->get();
        return view('admin.article.state.list', [
            'articleDetailsHistories' => $adh,
        ]);
    }

    public function showStatistic($id)
    {

        return view('admin.article.statistic', [
            'article' => $article,
        ]);
    }

    public function listSuggestion(Request $request, User $user)
    {
        if ($user->isUser()) {
            return redirect('/');
        }
        $tags = $request->get('tags');
        $articles = [];
        if (!empty($tags)) {
            $articles = ArticleRepository::searchPendingMRs($user, explode(',', $tags));
        } else {
            $articles = ArticleRepository::suggestPendingArticles($user);
        }
        return view('admin.article.suggest_list',[
            'articles' => $articles,
            'tags'     => $tags,
        ]);
    }

    /**
     * Returns the validator or redirects to the specified route
     * @param Request $request
     * @param $redirectName
     * @param array $redirectParams
     * @return \Illuminate\Validation\Validator
     */
    private function validateArticle(Request $request, $redirectName, $redirectParams = [])
    {
        $values = $request->all();
        if (is_string($values['tag'])) {
            $values['tag'] = explode(',', $values['tag']);
        }
        $validator = \Validator::make($values, [
            'title'    => 'required|min:5|max:255',
            'context'  => 'required|min:250',
            'language' => ['required', Rule::in(array_keys(Language::all()))],
            'tag'      => 'required|array|min:1|max:5',
        ]);
        if ($validator->fails()) {
            redirect(route($redirectName, $redirectParams))
                ->withErrors($validator)
                ->withInput()->send();
        }
        return $validator;
    }
}
