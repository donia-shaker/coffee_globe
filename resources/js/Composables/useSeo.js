import { usePage } from '@inertiajs/vue3'

const SITE_URL = 'https://coffeeglobe.sa'
const SITE_NAME = 'Coffee Globe'
const DEFAULT_IMAGE = `${SITE_URL}/images/bg_slide.png`
const DEFAULT_OG_WIDTH = '1200'
const DEFAULT_OG_HEIGHT = '630'

/**
 * SEO Meta helper for bilingual support.
 * Reads the current locale from Inertia shared props.
 */
export function useSeoMeta({ title, titleEn, description, descriptionEn, keywords, keywordsEn, image, url, type = 'website' }) {
    const page = usePage()
    const locale = page.props.locale || 'ar'

    const fullTitle = title
        ? `${locale === 'ar' ? title : (titleEn || title)} | ${SITE_NAME}`
        : `${SITE_NAME} - ${locale === 'ar' ? 'شركة كوفى جلوب لخدمات تجارة البن اليمنى' : 'Premium Yemen Coffee Trading Company'}`

    const desc = locale === 'ar'
        ? (description || '')
        : (descriptionEn || description || '')

    const kw = locale === 'ar'
        ? (keywords || '')
        : (keywordsEn || keywords || '')

    const ogImage = image
        ? (image.startsWith('http') ? image : `${SITE_URL}${image}`)
        : DEFAULT_IMAGE

    const currentUrl = url || (typeof window !== 'undefined' ? window.location.href : '')

    return {
        title: fullTitle,
        description: desc,
        keywords: kw,
        canonical: currentUrl,
        ogTitle: fullTitle,
        ogDescription: desc,
        ogImage,
        ogUrl: currentUrl,
        ogType: type,
        ogImageWidth: DEFAULT_OG_WIDTH,
        ogImageHeight: DEFAULT_OG_HEIGHT,
        twitterCard: 'summary_large_image',
        twitterTitle: fullTitle,
        twitterDescription: desc,
        twitterImage: ogImage,
        locale,
    }
}

export { SITE_URL, SITE_NAME }
