<template>
    <form class="uk-margin-large" @submit.prevent="save">
        <div class="uk-modal-header">
            <h1 class="uk-h3">{{ 'Discord Webhook Settings' | trans }}</h1>
        </div>
        <div class="uk-modal-body">
            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Webhook Uri' | trans}}</label>
                <div class="uk-form-controls">
                    <input type="text" class="uk-input uk-form-large uk-width-expand" v-model="package.config.webhook_uri">
                </div>
            </div>

            <div class="uk-margin">
                <p v-for="(pkg , id) in package.config.pkgs" :key="id"><label><input type="checkbox" v-model="pkg.active" class="uk-checkbox uk-margin-small-right"> {{pkg.title}}</label></p>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-primary" type="submit">{{ 'Save' | trans }}</button>
        </div>
    </form>
</template>

<script>

    module.exports = {
        name: 'discord',

        settings: true,

        props: ['package'],

        methods: {
            save() {
               this.$http.post('admin/system/settings/config', {
                   name: 'discord',
                   config: this.package.config
               }).then(function () {
                   this.$notify('Settings saved.', '');
               }).catch(function (response) {
                   this.$notify(response.message, 'danger');
               }).finally(function () {
                   this.$parent.close();
               });
           }
       }
    };

    window.Extensions.components['discord-settings'] = module.exports;
</script>
