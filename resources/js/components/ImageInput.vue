<template>
    <div>
        <picture-input
                name="avatar"
                ref="pictureInput"
                @change="toggle"
                @remove="toggle"
                @prefill="isUpdateAvatar = 0"
                width="350"
                height="350"
                margin="16"
                accept="image/jpeg,image/png"
                size="10"
                buttonClass="btn btn-primary"
                removeButtonClass="btn btn-danger"
                :prefill="prefill"
                :removable="true"
                :hideChangeButton="false">
        </picture-input>
        <input name="is_update_avatar" type="hidden" v-model="isUpdateAvatar">
    </div>
</template>

<script>
    import PictureInput from 'vue-picture-input'
    export default {
        name: "ImageInput",
        props: ['prefill'],
        data: () => ({
            isUpdateAvatar: 0,
        }),
        components: {PictureInput},
        methods: {
            toggle () {
                this.isUpdateAvatar = 1;
            }
        }
    }
</script>

<style scoped>

</style>