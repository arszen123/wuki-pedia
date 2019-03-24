<template>
    <div>
        <div class="col-md-12" id="lang-element">
            <div class="col-md-5" style="display: inline-block">
                <label for="lang_id-placeholder"
                       class="col-form-label text-md-right justify-content-center">Langauge</label>

                <select id="lang_id-placeholder" v-model="form.lang_id"
                        class="form-control">
                    <option v-for="(langItem,i) in langList" :value="i">{{langItem.isoName}}</option>
                </select>
            </div>

            <div class="col-md-5" style="display: inline-block">
                <label for="type-placeholder" class="col-form-label text-md-right justify-content-center">Type</label>

                <select id="type-placeholder" v-model="form.type" class="form-control">
                    <option v-for="(typeItem,i) in typeList" :value="i">{{typeItem.name}}</option>
                </select>
            </div>
            <button style="display: inline-block" class="btn btn-primary" v-on:click.prevent="addLangField">Add</button>
        </div>
        <div class="languages">
            <div class="language" v-for="(language,i) in currentLanguages" :key="language.lang_id">
                <input type="hidden" :name="`lang[${language.lang_id}][lang_id]`" :value="language.lang_id">
                <input type="hidden" :name="`lang[${language.lang_id}][type]`" :value="language.type">
                <div>{{ langList[language.lang_id].isoName }} ({{ typeList[language.type].name }})</div>
                <button class="btn btn-danger" v-on:click.prevent="deleteLang(i)">Delete</button>
                <button class="btn btn-primary" v-on:click.prevent="editLang(i)">Edit</button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: "LanguageFormElement",
        props: ['languages'],
        data: () => ({
            langList: [],
            typeList: [],
            currentLanguages: [],
            form: {
                lang_id: null,
                type: null,
            }
        }),
        created: function () {
            this.currentLanguages = this.languages;
            axios.get('//' + window.location.host + '/api/languages').then(value => {
                this.langList = value.data;
            });
            axios.get('//' + window.location.host + '/api/language/types').then(value => {
                this.typeList = value.data;
            });
        },
        methods: {
            addLangField() {
                if (this.form.lang_id === null || this.form.type === null) {
                    return;
                }
                let isSet = false;
                let temp = [];
                for (let lang of this.currentLanguages) {
                    if (this.form.lang_id === lang.lang_id) {
                        temp.push(this.form);
                        isSet = true;
                    } else {
                        temp.push(lang);
                    }
                }
                if (isSet) {
                    this.currentLanguages = temp;
                } else {
                    this.currentLanguages.push(this.form);
                }
                this.form = {
                    lang_id: null,
                    type: null,
                }
            },
            deleteLang(i) {
                this.$delete(this.currentLanguages, i);
            },
            editLang(i) {
                this.form = this.currentLanguages[i];
            }
        }
    }
</script>

<style scoped>
.languages {
    text-align: center;
    margin: 25px;
}
</style>