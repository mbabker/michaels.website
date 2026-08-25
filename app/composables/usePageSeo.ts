type PageSeo = {
    title?: string
    description?: string
}

/**
 * Applies a content page's SEO fields and mirrors them onto Open Graph and Twitter, so social cards
 * carry a real title and description instead of falling back to the document title. Use this in
 * place of a bare `useSeoMeta(page.seo)`.
 *
 * og:title deliberately omits the site name that `app.head.titleTemplate` appends to `<title>` —
 * og:site_name already carries it.
 */
export function usePageSeo(seo: PageSeo | undefined | null) {
    if (!seo) {
        return
    }

    useSeoMeta({
        title: seo.title,
        description: seo.description,
        ogTitle: seo.title,
        ogDescription: seo.description,
        twitterTitle: seo.title,
        twitterDescription: seo.description,
    })
}
