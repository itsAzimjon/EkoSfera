import { isEmpty, reject } from "lodash"
import axios from "../../helpers/axios"
import  { setItem } from "../../helpers/Storage"
const state ={
    isLoading:true,
    user:null,
    errors:null,
    permission: null,
}
const mutations = {
    LoginStart(state){
        state.isLoading=true
        state.user=null
        state.errors=null

    },
    LoginSuccess(state,payload){
        state.isLoading=false
        state.user=payload.user
        state.permission= payload.permission
        state.errors=null
    },
    LoginError(state,payload){
        state.isLoading=false
        state.errors=payload
    },
    StartCheck(state){
        state.isLoading=true,
        state.user=null
        state.errors=null
    },
    LogoutSuccess(state){
        state.isLoading=false,
        state.user=null
        state.errors=null
    },
    LogoutError(state){
        state.isLoading=false,
        state.user=null
        state.errors=['Logout bo\'lmadi']
    }


}
const actions = {
    Login(context,date){
        return new Promise((resolve,reject)=>{
            context.commit("LoginStart")
            axios.post('login',date).then(response=>{
                context.commit("LoginSuccess",response.data)
                setItem('token',response.data.token)
                resolve('success')
            }).catch(error=>{
                if(isEmpty(error.response)){
                    context.commit("LoginError",['Server ishlamayapdi'])
                    reject("Server ishlamayapdi")
                }
                else{
                    context.commit("LoginError",error.response.data.error)
                    reject(error.response.data.error)
                }
            });
        })
    },
    check(context){
        return new Promise((resolve,reject)=>{
            context.commit("StartCheck")
            axios.get('/user').then(response=>{
                context.commit("LoginSuccess",response.data)
                resolve('success')
            }).catch(error=>{
                if(isEmpty(error.response)){
                    context.commit("LoginError",['Server ishlamayapdi'])
                    reject("Server ishlamayapdi")
                }
                else{
                    context.commit("LoginError",error.response.data.error)
                    reject(error.response.data.error)
                }
            });
        })
    },
    Logout(context){
        return new Promise((resolve,reject)=>{
            context.commit("LoginStart")
            axios.post('logout').then(response=>{
                context.commit("LogoutSuccess")
                setItem('token',null)
                resolve('success')
            }).catch(error=>{
                if(isEmpty(error.response)){
                    context.commit("LoginError",['Server ishlamayapdi'])
                    reject("Server ishlamayapdi")
                }
                else{
                    context.commit("LoginError",error.response.data.error)
                    reject(error.response.data.error)
                }   
            });
        })
    }

}
export default {state,mutations,actions}