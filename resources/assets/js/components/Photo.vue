<template>
    <div class="photo">
        <figure class="photo__wrapper">
            <img 
                class="photo__image photo__image--portrait"
                :src="item.filename"
                :alt="`Photo by ${item.user.name}`"
            >
        </figure>
        <RouterLink
            class="photo__overlay"
            :to="`/photos/${item.id}`"
            :title="`View the photo by ${item.user.name}`"
        >
        <div class="photo__controls">
            <button
                class="photo__action photo__action--like"
                title="Like photo"
            >
                <i class="icon ion-md-heart"></i>12
            </button>
            <a 
                class="photo__action"
                title="Download photo"
                @click.stop
                :href="`/photos/${item.id}/download`"
            >
                <i class="icon ion-md-arrow-round-down"></i>
            </a>
        </div>
        <div class="photo__username">
            {{ item.user.name }}
        </div>
        </RouterLink>
    </div>
</template>

<script>
export default {
    props: {
        item: {
            type: Object,
            required: true
        }
    }
}
</script>
