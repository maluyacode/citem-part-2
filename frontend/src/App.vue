<template>
  <div class="container mt-4">
      <header class="mf-header">
        <div>
          <h1 class="mf-brand-title">Manila FAME</h1>
          <div class="mf-brand-sub">Premier trade show for Philippine design & craftsmanship</div>
        </div>
      </header>
      <div class="card p-3 mt-3 mb-3 mf-card">
      <template v-if="step === 4">
        <div class="text-center">
          <h2>Thank you!</h2>
          <p>Your registration has been received.</p>
        </div>
      </template>
      <template v-else>
        <component :is="currentComponent" :modelValue="formData" @update:modelValue="updateData" @next="nextStep" @back="prevStep" @submitted="onSubmitted" @validation-errors="setErrors" @go-to-step="goToStep" :errors="errors"></component>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import AccountInformation from './components/AccountInformation.vue'
import CompanyInformation from './components/CompanyInformation.vue'
import RegistrationSummary from './components/RegistrationSummary.vue'

const step = ref(1)
const formData = ref({ account: {}, company: {} })
const errors = ref({})

const components = {
  1: AccountInformation,
  2: CompanyInformation,
  3: RegistrationSummary,
}

function updateData(payload) {
  formData.value = { ...formData.value, ...payload }
}

function nextStep() { step.value = Math.min(3, step.value + 1) }
function prevStep() { step.value = Math.max(1, step.value - 1) }

function onSubmitted() {
  step.value = 4
}

const currentComponent = computed(() => components[step.value] || AccountInformation)

function setErrors(errs) {
  // Normalize Laravel and client error shapes into { 'field.key': 'message' }
  const out = {}
  if (!errs) {
    errors.value = {}
    return
  }

  function pushField(k, v) {
    if (Array.isArray(v)) out[k] = v[0]
    else if (typeof v === 'string') out[k] = v
    else out[k] = String(v)
  }

  for (const k of Object.keys(errs)) {
    const v = errs[k]
    if (k && typeof v === 'object' && !Array.isArray(v)) {
      // nested object (e.g., { company: { company_name: ['msg'] } })
      for (const sub of Object.keys(v)) {
        const val = v[sub]
        pushField(`${k}.${sub}`, val)
      }
    } else {
      pushField(k, v)
    }
  }

  errors.value = out
}

function goToStep(n) {
  step.value = n
}
</script>

<style>
@import 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';
</style>
