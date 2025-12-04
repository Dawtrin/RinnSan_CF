import { Button, Card, Space, Table, Tag, Form, Input } from 'antd'

const columns = [
  { title: 'Tên', dataIndex: 'name' },
  { title: 'Trạng thái', dataIndex: 'status', render: s => <Tag color={s === 'active' ? 'green' : 'red'}>{s}</Tag> }
]

const data = [
  { key: 1, name: 'Croissant', status: 'active' },
  { key: 2, name: 'Tiramisu', status: 'inactive' }
]

export default function UIDemo() {
  const [form] = Form.useForm()
  const onFinish = values => console.log(values)
  return (
    <div style={{ maxWidth: 900, margin: '24px auto', padding: 16 }}>
      <Space direction="vertical" style={{ width: '100%' }} size="large">
        <Card title="Ant Design Demo">
          <Space>
            <Button type="primary">Primary</Button>
            <Button>Default</Button>
            <Button danger>Danger</Button>
          </Space>
        </Card>

        <Card title="Bảng">
          <Table columns={columns} dataSource={data} pagination={false} />
        </Card>

        <Card title="Form">
          <Form form={form} layout="inline" onFinish={onFinish}>
            <Form.Item name="name" rules={[{ required: true }]}> 
              <Input placeholder="Tên món" />
            </Form.Item>
            <Form.Item>
              <Button type="primary" htmlType="submit">Lưu</Button>
            </Form.Item>
          </Form>
        </Card>
      </Space>
    </div>
  )
}

