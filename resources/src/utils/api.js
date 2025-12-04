import axios from 'axios'

const api = axios.create({ baseURL: '/api' })

export const getSuppliers = async ({ page = 1, per_page = 20, is_active } = {}) => {
  const params = { page, per_page }
  if (typeof is_active !== 'undefined') params.is_active = is_active
  const res = await api.get('/admin/suppliers', { params })
  return res.data
}

export const createSupplier = async (data) => {
  const res = await api.post('/admin/suppliers', data)
  return res.data
}

export const updateSupplier = async (id, data) => {
  const res = await api.put(`/admin/suppliers/${id}`, data)
  return res.data
}

export const deleteSupplier = async (id) => {
  const res = await api.delete(`/admin/suppliers/${id}`)
  return res.data
}

