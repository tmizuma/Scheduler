
}<style scoped>
    .page_loading img {
        z-index: 999;
        position: absolute;
        top: 0px;
        right: 0px;
        bottom: 0px;
        left: 0px;
        margin: auto;
    }
    .display_blinder{
        position:fixed;
        width:100%;
        height:100%;
        left:0;
        top:0;
        filter:alpha(opacity=40);
        -moz-opacity:0.40;
        opacity:0.40;
        z-index:100;
    }
</style>
<template>
    <div class="display_blinder">
        <div class="page_loading">
        <img src="img/loading.gif">
</div>
</div>
</template>

<script>
</script>

