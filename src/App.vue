<script setup>
import { RouterLink, RouterView } from "vue-router";


</script>

<template>
    <RouterView />
    <teleport to="head">
        <component :is="'script'" type="application/ld+json">
            {
            "@context": "https://schema.org",
            "@type": "Person",
            "address": {
            "@type": "PostalAddress",
            "addressLocality": "Dhaka",
            "addressRegion": "Dhaka",
            "postalCode": "1216",
            "streetAddress": "525, Senpara Parbata, Mirpur-10, Dhaka"
            },
            "colleague": [
            "https://www.facebook.com/mamunhbg",
            "https://www.facebook.com/ohabulislam"
            ],
            "email": "mailto:sujan@kalni.net",
            "image": "https://i.imgur.com/zsQnEbE.png",
            "jobTitle": "CEO (Chief Executive Officer)",
            "name": "Sujan C.Barty",
            "telephone": "+880 1708 513203",
            "url": "https://sujan.kalni.net"
            }
        </component>
    </teleport>
</template>
