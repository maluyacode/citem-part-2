<template>
  <div>
    <h3>Step 3 — Summary & Confirmation</h3>
    <div class="mb-3">
      <h5>Account</h5>
      <p><strong>Name:</strong> {{ data.account.first_name }} {{ data.account.last_name }}</p>
      <p><strong>Email:</strong> {{ data.account.email }}</p>
      <p><strong>Username:</strong> {{ data.account.username }}</p>
      <p><strong>Participation:</strong> {{ data.account.type_of_participation }}</p>
    </div>

    <div class="mb-3">
      <h5>Company</h5>
      <p><strong>Company:</strong> {{ data.company.company_name }}</p>
      <p><strong>Address:</strong> {{ data.company.address_line }}, {{ data.company.town_city }}, {{ data.company.region_state }}, {{ data.company.country }}</p>
      <p><strong>Year Established:</strong> {{ data.company.year_established }}</p>
      <p v-if="data.company.website"><strong>Website:</strong> {{ data.company.website }}</p>
      <p v-if="data.company.brochure"><strong>Brochure:</strong> {{ data.company.brochure.name || data.company.brochure }}</p>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-secondary" @click="$emit('back')">Back</button>
      <button class="btn btn-primary" @click="submit" :disabled="submitting">Submit Registration</button>
    </div>

    <div class="mt-3 text-success" v-if="success">{{ success }}</div>
    <div class="mt-3 text-danger" v-if="submitError">{{ submitError }}</div>
  </div>
</template>

<script setup>
import api from '../api'
import { ref } from 'vue'
import { validateAccount, validateCompany } from '../validation'
const props = defineProps({ modelValue: Object })
const emit = defineEmits(['submitted', 'validation-errors', 'go-to-step'])

const data = props.modelValue || { account: {}, company: {} }
const submitting = ref(false)
const success = ref('')
const submitError = ref('')

async function submit() {
  // run client-side validation before posting
  const aErrors = validateAccount(data.account || {})
  const cErrors = validateCompany(data.company || {})
  const merged = { ...aErrors, ...cErrors }
  if (Object.keys(merged).length) {
    emit('validation-errors', merged)
    // decide which step to show: account errors -> step 1, else step 2
    const step = Object.keys(merged).some(k => k.startsWith('company.')) ? 2 : 1
    emit('go-to-step', step)
    return
  }

  submitting.value = true
  submitError.value = ''
  success.value = ''
  try {
    const formData = new FormData()
    const a = data.account
    formData.append('first_name', a.first_name)
    formData.append('last_name', a.last_name)
    formData.append('email', a.email)
    formData.append('username', a.username)
    formData.append('password', a.password)
    formData.append('password_confirmation', a.password_confirmation)
    formData.append('type_of_participation', a.type_of_participation)

    const c = data.company
    formData.append('company[company_name]', c.company_name)
    formData.append('company[address_line]', c.address_line)
    formData.append('company[town_city]', c.town_city)
    formData.append('company[region_state]', c.region_state)
    formData.append('company[country]', c.country)
    formData.append('company[year_established]', c.year_established)
    if (c.website) formData.append('company[website]', c.website)
    if (c.brochure) formData.append('company[brochure]', c.brochure)

    const res = await api.post('/api/register', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
    success.value = res.data.message || 'Registration successful.'
    emit('submitted')
  } catch (err) {
    submitError.value = err.response?.data?.message || 'Submission failed.'
    // surface server validation errors if present
    const serverErrors = err.response?.data?.errors
    if (serverErrors) emit('validation-errors', serverErrors)
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped></style>
