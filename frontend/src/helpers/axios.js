import axios from 'axios'
import { getItem } from '../helpers/Storage'

// axios.defaults.baseURL="http://api.ekosfera.uz/api"
axios.defaults.baseURL="http://127.0.0.1:8000/api"
axios.interceptors.request.use(config=>{
    const token=getItem('token')
    const authorization = token ? 'Bearer '+token:""
    console.log(authorization);
    config.headers.Authorization=authorization
    return config
})
export default axios