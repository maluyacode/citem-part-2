export function validateAccount(form) {
    const errors = {}
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    const usernameRe = /^[a-zA-Z0-9]+$/
    const allowed = ['Buyer', 'Exhibitor', 'Visitor']

    if (!form.first_name || !form.first_name.trim()) errors.first_name = 'First name is required.'
    if (!form.last_name || !form.last_name.trim()) errors.last_name = 'Last name is required.'
    if (!form.email || !emailRe.test(form.email)) errors.email = 'A valid email is required.'
    if (!form.username || !usernameRe.test(form.username)) errors.username = 'Username is required and must be alphanumeric.'
    if (!form.password || form.password.length < 8) errors.password = 'Password must be at least 8 characters.'
    if (form.password !== form.password_confirmation) errors.password = 'Password confirmation does not match.'
    if (!form.type_of_participation || !allowed.includes(form.type_of_participation)) errors.type_of_participation = 'Please select a type of participation.'

    return errors
}

export function validateCompany(form) {
    const errors = {}
    const currentYear = new Date().getFullYear()

    if (!form.company_name || !form.company_name.trim()) errors['company.company_name'] = 'Company name is required.'
    if (!form.address_line || !form.address_line.trim()) errors['company.address_line'] = 'Address line is required.'
    if (!form.town_city || !form.town_city.trim()) errors['company.town_city'] = 'Town/City is required.'
    if (!form.region_state || !form.region_state.trim()) errors['company.region_state'] = 'Region/State is required.'
    if (!form.country || !form.country.trim()) errors['company.country'] = 'Country is required.'

    if (!form.year_established || !/^\d{4}$/.test(String(form.year_established))) {
        errors['company.year_established'] = 'Year established is required and must be 4 digits.'
    } else if (Number(form.year_established) > currentYear) {
        errors['company.year_established'] = 'The year established may not be in the future.'
    }

    if (form.website) {
        try {
            const u = new URL(form.website)
            if (!['http:', 'https:'].includes(u.protocol)) throw new Error('invalid')
        } catch (e) {
            errors['company.website'] = 'Website must be a valid URL.'
        }
    }

    if (form.brochure) {
        const f = form.brochure
        const max = 2 * 1024 * 1024
        const name = (f.name || '').toLowerCase()
        if (f.size > max) errors['company.brochure'] = 'Brochure must be at most 2MB.'
        if (!name.endsWith('.pdf') && !name.endsWith('.doc') && !name.endsWith('.docx')) errors['company.brochure'] = 'Allowed brochure types: PDF, DOC, DOCX.'
    }

    return errors
}
