import { useEffect, useState } from 'react'
import { getSuppliers, createSupplier, deleteSupplier } from '../utils/api'

export default function AdminSuppliers() {
  const [items, setItems] = useState([])
  const [pagination, setPagination] = useState(null)
  const [loading, setLoading] = useState(false)
  const [name, setName] = useState('')
  const [message, setMessage] = useState('')

  const fetchData = async (page = 1) => {
    setLoading(true)
    try {
      const res = await getSuppliers({ page, per_page: 10 })
      if (res.success) {
        setItems(res.data)
        setPagination(res.pagination || null)
      } else {
        setMessage(res.message || 'Lỗi tải dữ liệu')
      }
    } catch (e) {
      setMessage('Lỗi kết nối API')
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchData(1)
  }, [])

  const onCreate = async (e) => {
    e.preventDefault()
    if (!name.trim()) return
    setLoading(true)
    try {
      const res = await createSupplier({ name })
      if (res.success) {
        setName('')
        setMessage('Tạo supplier thành công')
        fetchData(pagination?.current_page || 1)
      } else {
        setMessage(res.message || 'Tạo supplier thất bại')
      }
    } catch (e) {
      setMessage('Lỗi kết nối API')
    } finally {
      setLoading(false)
    }
  }

  const onDelete = async (id) => {
    if (!confirm('Xóa supplier này?')) return
    setLoading(true)
    try {
      const res = await deleteSupplier(id)
      if (res.success) {
        setMessage('Xóa supplier thành công')
        fetchData(pagination?.current_page || 1)
      } else {
        setMessage(res.message || 'Xóa supplier thất bại')
      }
    } catch (e) {
      setMessage('Lỗi kết nối API')
    } finally {
      setLoading(false)
    }
  }

  return (
      <div className="max-w-6xl mx-auto px-4 py-8">
        <h1 className="text-2xl font-semibold">Admin - Suppliers</h1>
        {message && <p className="mt-2 text-sm text-gray-600">{message}</p>}

        <form onSubmit={onCreate} className="mt-4 flex gap-2">
          <input
            type="text"
            placeholder="Tên supplier"
            value={name}
            onChange={(e) => setName(e.target.value)}
            className="border px-3 py-2 rounded-md flex-1"
          />
          <button type="submit" disabled={loading} className="px-4 py-2 bg-amber-600 text-white rounded-md">
            Tạo
          </button>
        </form>

        {loading ? (
          <p className="mt-6">Đang tải...</p>
        ) : (
          <div className="mt-6 overflow-x-auto">
            <table className="min-w-full text-left">
              <thead>
                <tr>
                  <th className="px-3 py-2">ID</th>
                  <th className="px-3 py-2">Tên</th>
                  <th className="px-3 py-2">Điện thoại</th>
                  <th className="px-3 py-2">Email</th>
                  <th className="px-3 py-2">Hành động</th>
                </tr>
              </thead>
              <tbody>
                {items.map((s) => (
                  <tr key={s.id} className="border-t">
                    <td className="px-3 py-2">{s.id}</td>
                    <td className="px-3 py-2">{s.name}</td>
                    <td className="px-3 py-2">{s.phone || '-'}</td>
                    <td className="px-3 py-2">{s.email || '-'}</td>
                    <td className="px-3 py-2">
                      <button onClick={() => onDelete(s.id)} disabled={loading} className="px-3 py-1 bg-red-600 text-white rounded-md">Xóa</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {pagination && (
          <div className="mt-4 flex items-center gap-3">
            <button
              onClick={() => fetchData(Math.max(1, pagination.current_page - 1))}
              disabled={loading || pagination.current_page <= 1}
              className="px-3 py-2 border rounded-md"
            >
              Trang trước
            </button>
            <span>
              Trang {pagination.current_page}/{pagination.total_pages}
            </span>
            <button
              onClick={() => fetchData(Math.min(pagination.total_pages, pagination.current_page + 1))}
              disabled={loading || pagination.current_page >= pagination.total_pages}
              className="px-3 py-2 border rounded-md"
            >
              Trang sau
            </button>
          </div>
        )}
      </div>
  )
}
