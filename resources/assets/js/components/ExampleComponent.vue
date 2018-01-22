
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">
                        I'm an example component!
                    </div>
                </div>
                <test-component></test-component>
            </div>
        </div>
    </div>
</template>

<script>
    import TestComponent from './TestComponent.vue'
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        components: {
            TestComponent
        }
    }
</script>

