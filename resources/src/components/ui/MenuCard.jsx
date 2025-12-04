export default function MenuCard({ title, price, image, onClick }) {
  return (
    <div className="rounded-xl overflow-hidden bg-white shadow-sm">
      <div className="h-36" style={{backgroundImage:`url(${image})`, backgroundSize:'cover', backgroundPosition:'center'}}></div>
      <div className="p-4 flex items-center justify-between">
        <div>
          <p className="font-semibold">{title}</p>
          <p className="text-gray-500">{price}</p>
        </div>
        <button className="px-3 py-2 bg-amber-600 text-white rounded-md" onClick={onClick}>Đặt món</button>
      </div>
    </div>
  )}

