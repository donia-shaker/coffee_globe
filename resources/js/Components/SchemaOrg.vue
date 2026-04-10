<script setup>
import { computed, onMounted, onBeforeUnmount, watch } from 'vue'

const props = defineProps({
    type: {
        type: String,
        default: 'organization',
        validator: (v) => ['organization', 'localBusiness', 'blogPost', 'breadcrumb', 'faq'].includes(v),
    },
    data: {
        type: Object,
        default: () => ({}),
    },
})

const schema = computed(() => {
    const siteUrl = 'https://coffeeglobe.sa'

    switch (props.type) {
        case 'organization':
            return {
                '@context': 'https://schema.org',
                '@type': 'Organization',
                name: 'Coffee Globe',
                alternateName: 'كوفى جلوب لخدمات تجارة البن اليمنى',
                url: siteUrl,
                logo: `${siteUrl}/images/logo.png`,
                description: props.data.description || 'شركة رائدة في تجارة وتصدير القهوة اليمنية الفاخرة',
                address: {
                    '@type': 'PostalAddress',
                    addressCountry: 'SA',
                    addressLocality: props.data.addressLocality || 'الرياض',
                },
                contactPoint: {
                    '@type': 'ContactPoint',
                    contactType: 'customer service',
                    telephone: props.data.telephone || '',
                    availableLanguage: ['Arabic', 'English'],
                },
                sameAs: props.data.sameAs || [],
            }

        case 'localBusiness':
            return {
                '@context': 'https://schema.org',
                '@type': 'LocalBusiness',
                '@id': `${siteUrl}/#business`,
                name: 'Coffee Globe',
                alternateName: 'كوفى جلوب',
                image: `${siteUrl}/images/logo.png`,
                url: siteUrl,
                telephone: props.data.telephone || '',
                email: props.data.email || '',
                address: {
                    '@type': 'PostalAddress',
                    streetAddress: props.data.streetAddress || '',
                    addressLocality: props.data.addressLocality || 'الرياض',
                    addressRegion: props.data.addressRegion || 'الرياض',
                    postalCode: props.data.postalCode || '',
                    addressCountry: 'SA',
                },
                geo: props.data.geo
                    ? {
                          '@type': 'GeoCoordinates',
                          latitude: props.data.geo.latitude,
                          longitude: props.data.geo.longitude,
                      }
                    : undefined,
                priceRange: '$$',
                openingHoursSpecification: props.data.openingHours || undefined,
                sameAs: props.data.sameAs || [],
            }

        case 'blogPost':
            return {
                '@context': 'https://schema.org',
                '@type': 'BlogPosting',
                headline: props.data.headline || '',
                image: props.data.image || `${siteUrl}/images/logo.png`,
                datePublished: props.data.datePublished || '',
                dateModified: props.data.dateModified || props.data.datePublished || '',
                author: {
                    '@type': 'Organization',
                    name: 'Coffee Globe',
                    url: siteUrl,
                },
                publisher: {
                    '@type': 'Organization',
                    name: 'Coffee Globe',
                    logo: {
                        '@type': 'ImageObject',
                        url: `${siteUrl}/images/logo.png`,
                    },
                },
                description: props.data.description || '',
                mainEntityOfPage: {
                    '@type': 'WebPage',
                    '@id': props.data.url || '',
                },
            }

        case 'breadcrumb':
            return {
                '@context': 'https://schema.org',
                '@type': 'BreadcrumbList',
                itemListElement: (props.data.items || []).map((item, index) => ({
                    '@type': 'ListItem',
                    position: index + 1,
                    name: item.name,
                    item: item.url ? `${siteUrl}${item.url}` : undefined,
                })),
            }

        case 'faq':
            return {
                '@context': 'https://schema.org',
                '@type': 'FAQPage',
                mainEntity: (props.data.items || []).map((item) => ({
                    '@type': 'Question',
                    name: item.question,
                    acceptedAnswer: {
                        '@type': 'Answer',
                        text: item.answer,
                    },
                })),
            }

        default:
            return {}
    }
})

let scriptElement = null

function injectSchema() {
    removeSchema()
    scriptElement = document.createElement('script')
    scriptElement.type = 'application/ld+json'
    scriptElement.textContent = JSON.stringify(schema.value)
    document.head.appendChild(scriptElement)
}

function removeSchema() {
    if (scriptElement) {
        scriptElement.remove()
        scriptElement = null
    }
}

onMounted(() => {
    injectSchema()
})

watch(schema, () => {
    injectSchema()
})

onBeforeUnmount(() => {
    removeSchema()
})
</script>

<template>
    <!-- Schema.org JSON-LD is injected via DOM API in onMounted -->
</template>
