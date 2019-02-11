import { OK } from '../util'

const state = {
    user: null,
    apiStatus: null
}

const getters = {
    check: state => !! state.user,
    username: state => state.user ? state.user.name : ''
}

const mutations = {
    setUser (state, user) {
        state.user = user
      }
}

const actions = {
    async register (context, data) {
        const response = await axios.post('/api/register', data)
        context.commit('setUser', response.data)
      },
    async login (context, data) {
        context.commit('setApiStatus', null)//通信ステータスの更新　最初はnull
        const response = await axios.post('/api/login', data).catch(err => err.response || err)

        if(response.status === OK){
            context.commit('setApiStatus', true)//通信ステータスの更新　成功したらtrue
            context.commit('setUser', response.data)
            return false
        }
        context.commit('setApiStatus', false)//通信ステータスの更新　失敗したらfalse
        context.commit('error/setCode', response.status, { root: true })//別モジュールのミューテーションを呼び出す
      },
    async logout (context) {
        const response = await axios.post('/api/logout')
        context.commit('setUser', null)
      },
    async currentUser(context){
        const response = await axios.get('/api/user')
        const user = response.data || null
        context.commit('setUser', user)
      }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}