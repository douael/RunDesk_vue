<template>
    <div class="card w-100 mt-2">
        <div class="card-body">
            {{ name }}
            {{ isActive }}
            {{ serialNum }}
        </div>
    </div>
</template>

<script>
    export default {
        name: 'material',
        props: ['name','isActive','serialNum'],
    }
</script>