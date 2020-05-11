const commonFn = {
  j2s(obj) {
    return JSON.stringify(obj)
  },
  cloneJson(obj) {
    return JSON.parse(JSON.stringify(obj))
  },
  shallowRefresh(name) {
    router.replace({ path: '/refresh', query: { name: name }})
  },
  closeGlobalLoading() {
    setTimeout(() => {
      store.dispatch('showLoading', false)
    }, 0)
  },
  openGlobalLoading() {
    setTimeout(() => {
      store.dispatch('showLoading', true)
    }, 0)
  },

  toastMsg(type, msg) {
    switch (type) {
      case 'normal':
        bus.$message({
          message: msg,
          center: true
        });
        break;
      case 'success':
        bus.$message({
          message: msg,
          type: 'success',
          center: true
        });
        break;
      case 'warning':
        bus.$message({
          message: msg,
          type: 'warning',
          center: true
        });
        break;
      case 'error':
        bus.$message({
          message: msg,
          type: 'error',
          center: true
        });
        break;
    }
  },
  clearVuex(cate) {
    store.dispatch(cate, [])
  },
  getHasRule(val) {
    const moduleRule = 'admin'
    let userInfo = Lockr.get('userInfo')
    if (userInfo.id == 1) {
      return true
    } else {
      let authList = moduleRule + Lockr.get('authList')
      return _.includes(authList, val)
    }
  }
}

export default commonFn
