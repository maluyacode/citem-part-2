<template>
  <div>
    <h3>Step 2 — Company Information</h3>
    <form @submit.prevent="next">
      <div class="mb-3">
        <label class="form-label">Company Name</label>
        <input v-model="form.company_name" class="form-control" />
        <div class="text-danger" v-if="displayErrors['company.company_name']">{{ displayErrors['company.company_name'] }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Address Line</label>
        <input v-model="form.address_line" class="form-control" />
        <div class="text-danger" v-if="displayErrors['company.address_line']">{{ displayErrors['company.address_line'] }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Town/City</label>
        <input v-model="form.town_city" class="form-control" />
        <div class="text-danger" v-if="displayErrors['company.town_city']">{{ displayErrors['company.town_city'] }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Region/State</label>
        <input v-model="form.region_state" class="form-control" />
        <div class="text-danger" v-if="displayErrors['company.region_state']">{{ displayErrors['company.region_state'] }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Country</label>
        <select v-model="form.country" class="form-select">
          <option value="" disabled>Select country</option>
          <option v-for="c in countries" :key="c">{{ c }}</option>
        </select>
        <div class="text-danger" v-if="displayErrors['company.country']">{{ displayErrors['company.country'] }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Year Established</label>
        <input v-model="form.year_established" type="number" class="form-control" />
        <div class="text-danger" v-if="displayErrors['company.year_established']">{{ displayErrors['company.year_established'] }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Website (optional)</label>
        <input v-model="form.website" type="url" class="form-control" />
        <div class="text-danger" v-if="displayErrors['company.website']">{{ displayErrors['company.website'] }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Company Brochure (PDF/DOC/DOCX, max 2MB)</label>
        <input ref="file" type="file" @change="onFile" class="form-control" />
        <div class="text-danger" v-if="displayErrors['company.brochure']">{{ displayErrors['company.brochure'] }}</div>
        <div class="mt-2" v-if="form.brochure">
          <small>Selected file: <strong>{{ form.brochure.name || form.brochure }}</strong></small>
          <button type="button" class="btn btn-link btn-sm" @click="removeFile">Remove</button>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="button" class="btn btn-secondary" @click="$emit('back')">Back</button>
        <button class="btn btn-primary">Next</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, watch, onMounted, computed } from 'vue'
import api from '../api'
import { validateCompany } from '../validation'

const props = defineProps({ modelValue: Object, errors: Object })
const emit = defineEmits(['update:modelValue', 'next', 'back', 'validation-errors'])

const file = ref(null)

const form = reactive({ company_name: '', address_line: '', town_city: '', region_state: '', country: '', year_established: '', website: '', brochure: null })
const countries = ref([])
const localErrors = ref({})
const displayErrors = computed(() => ({ ...(props.errors || {}), ...(localErrors.value || {}) }))

if (props.modelValue && props.modelValue.company) {
  Object.assign(form, props.modelValue.company)
  // if parent holds a File for brochure, keep it
  if (props.modelValue.company.brochure) form.brochure = props.modelValue.company.brochure
}

watch(() => props.modelValue, (v) => {
  if (v && v.company) {
    Object.assign(form, v.company)
    if (v.company.brochure) form.brochure = v.company.brochure
  }
})

onMounted(async () => {
  try {
    const res = await api.get('/api/countries')
    countries.value = res.data
  } catch (e) {
    countries.value = ['Philippines']
  }
})

function onFile(e) {
  const f = e.target.files[0]
  form.brochure = f || null
  emit('update:modelValue', { company: { ...form } })
}

function removeFile() {
  form.brochure = null
  // clear native input
  if (file.value && file.value instanceof HTMLInputElement) file.value.value = null
  emit('update:modelValue', { company: { ...form } })
}

function next() {
  const v = validateCompany(form)
  if (Object.keys(v).length) {
    localErrors.value = v
    emit('validation-errors', v)
    return
  }
  localErrors.value = {}
  emit('validation-errors', {})
  emit('update:modelValue', { company: { ...form } })
  emit('next')
}
</script>

<style scoped></style>
