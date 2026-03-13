<template>

    <div class="card">

        {{ request.title }}

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
    padding:10px;
    margin-bottom:10px;
    border-radius:6px;
}

</style>
