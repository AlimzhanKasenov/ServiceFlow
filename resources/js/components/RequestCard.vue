<template>

    <div class="card">

        <div class="title">
            {{ request.title }}
        </div>

        <div class="meta">

            #{{ request.id }}

        </div>

    </div>

</template>

<script setup>

defineProps({
    request:Object
})

</script>

<style>

.card{
    background:white;
    padding:12px;
    border-radius:8px;
    margin-bottom:10px;
    cursor:grab;
    box-shadow:0 2px 4px rgba(0,0,0,0.05);
}

.card:hover{
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

.title{
    font-weight:500;
}

.meta{
    font-size:12px;
    color:#868e96;
    margin-top:4px;
}

</style>
