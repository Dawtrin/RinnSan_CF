export default function SectionTitle({ title, actionText, actionHref }) {
  return (
    <div className="flex items-end justify-between">
      <h2 className="text-3xl font-bold">{title}</h2>
      {actionText && actionHref && (
        <a href={actionHref} className="text-amber-700">{actionText}</a>
      )}
    </div>
  )
}

