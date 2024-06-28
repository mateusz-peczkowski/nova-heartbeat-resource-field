import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-nova-heartbeat-resource-field', IndexField)
  app.component('detail-nova-heartbeat-resource-field', DetailField)
  app.component('form-nova-heartbeat-resource-field', FormField)
})
