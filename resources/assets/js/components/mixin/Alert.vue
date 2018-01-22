<template>
<div>aaaa</div>
</template>
<script>
    export default {
        methods: {
            showSuccess(message) {
                $("#show-alert-success").text(message);
                $("#show-alert-success").fadeIn("slow");
                setTimeout(function(){
                    $("#show-alert-success").fadeOut("slow");
                },2500);
            },
            showFailed(message) {
                $("#show-alert-failed").text(message);
                $("#show-alert-failed").fadeIn("slow");
                setTimeout(function(){
                    $("#show-alert-failed").fadeOut("slow");
                },2500);
            }
        }
    }
</script>

