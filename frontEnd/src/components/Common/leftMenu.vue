<template>
    <div>
        <div class="msg-content">
            <!--1.因为是自定义上传，所有在标签中必须添加属性http-request并绑定一个方法，例如:‘http-request’=“uploadFile”，属性action随意绑定一个字符串即可-->
            <!--2.需设置headers头，‘Content-Type’:‘multipart/form-data’，这里我在标签中和请求中均有设置-->
            <!--3.参数需要formData格式，即	let formData = new FormData()-->
            <!--formData.append(‘file’,file) 必不可少-->
            <el-upload
                    class="avatar-uploader"
                    action="upload"
                    :headers="{'Content-Type':'multipart/form-data'}"
                    :auto-upload = "true"
                    :http-request="uploadFile"
                    :show-file-list="false"
                    :on-success="handleAvatarSuccess"
                    :before-upload="beforeAvatarUpload"
            >
                <el-image v-if="imageUrl" :src="imageUrl" fit="cover" alt="" class="avatar"></el-image>
                <!--<img v-if="imageUrl" src="../../../../uploads/avatars/avatar_01.jpg" alt="" class="avatar">-->
                <!--<img v-if="imageUrl" :src="" alt="" class="avatar">-->
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
            <div class="u-name">
                <!--跳转用户详情页（带参数：用户名）-->
                <router-link :to="{name:'Userdetail',query:{user_id:menuData[5]}}" style="color: black;">{{ menuData[0] }}</router-link>
            </div>
            <div class="n-name">{{ menuData[1] }}</div>
            <div class="level-bar">
                <router-link :to="'Help'" title="点击进入积分等级说明页">
                    <span class="score">{{ score }}</span>
                    <span class="level">{{ level }}</span>
                </router-link>
            </div>
            <div class="detail">
                <span class="label">发帖：</span>{{ invitations }}
                <span class="label">热门：</span>{{ hots }}
            </div>
            <div class="functions">
                <!-- <el-badge :value="msgCount" :max="99" class="count">
                    <router-link :to="'Help'" class="func">
                        <img src="../../assets/images/icons/icon-message.png" style="width: 24px;height: 24px;vertical-align: middle;" alt="">
                    </router-link>
                </el-badge> -->
                <router-link :to="{name:'Articleadd',query:{}}" class="func func-env">
                    <img src="../../assets/images/icons/icon-envelope.png" style="width: 24px;height: 24px;vertical-align: middle;" alt="">
                </router-link>
                <!-- <el-badge :value="envCount" :max="10" class="count">
                    
                </el-badge> -->
            </div>
            <div class="logout">
                <el-button type="primary" @click="logout">退出登录</el-button>
            </div>
        </div>
        <!-- <div class="msg-menu">
            <div :class="{active:active === '首页'}" @click="selected('首页')">
                <router-link :to="{name:'Index'}">
                    <img src="../../assets/images/icons/icon-home.png" alt="">首页
                </router-link>
            </div>
            <div :class="{active:active === '收藏'}" @click="selected('收藏')">
                <router-link :to="{name:'Help'}">
                    <img src="../../assets/images/icons/icon-collection.png" alt="">收藏
                </router-link>
            </div>

            <div :class="{active:active === '粉丝/关注'}" @click="selected('粉丝/关注')">
                <router-link :to="{name:'Help'}" >
                    <img src="../../assets/images/icons/icon-fans.png" alt="">粉丝/关注
                </router-link>
            </div>
        </div> -->
    </div>
</template>

<script>
    export default {
        // 从父组件 Home 传过来的参数
        // 在子组件中若想使用，使用方法为 this.参数 ，例如this.menuData
        props: ['menuData'],
        data() {
            return {
                imageUrl: this.menuData[2],
                score: 2.5,
                level: '青铜',
                msgCount: 100,
                envCount: 5,
                invitations: this.menuData[3],
                hots: this.menuData[4],
                active: '首页'
            }
        },
        created(){

        },
        methods: {

            handleAvatarSuccess(res, file) {
                this.imageUrl = URL.createObjectURL(file.raw);
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg' || 'image/jpg' || 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG、JPEG和PHG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            },

            // 上传头像
            uploadFile(params){
                const self = this;
                let file = params.file;

                const isJPG = file.type === 'image/jpeg' || 'image/jpg' || 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;
                console.log(file.name);
                console.log(file.type);

                if (!isJPG) {
                    self.$message.error('上传头像图片只能是 JPG、JPEG和PHG 格式!');
                    return isJPG;
                }
                if (!isLt2M) {
                    self.$message.error('上传头像图片大小不能超过 2MB!');
                    return isLt2M;
                }
                // return isJPG && isLt2M;

                const formData = new FormData();
                formData.append('file', file);

                self.axios({
                    url: 'http://vt.com/php/index.php/admin/users/uploadImg',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    if (response.data.code === 0) {
                        self.$message.error(response.data['error'])
                    }else {
                        self.imageUrl = response.data['data'];
                        console.log(self.imageUrl);
                        // 头像地址写进数据库
                        self.saveImgUrl(response.data['data']);
                        Lockr.set('imgUrl',response.data['data']);
                    }
                })
            },

            saveImgUrl(imgUrl){
                const self = this;
                // const url = imgUrl;
                let data = {
                    'username': self.menuData[0],
                    'imageUrl': imgUrl
                };

                self.axios.post('http://vt.com/php/index.php/admin/users/saveImg',data).then(function (res) {
                    if (res.status === 200 && res.data['error'] === ''){
                        _g.toastMsg('success', '上传头像成功');
                    }else {
                        _g.toastMsg('error', '上传失败，请稍后重试');
                    }
                }).catch(function (error) {
                    if(error.status === 504){
                        bus.$message({
                            message: '请求超时，请检查网络',
                            type: 'warning'
                        })
                    }
                })
            },

            // 退出登录
            logout(){
                const self = this;
                self.$confirm('确认退出吗？', '提示', {
                    confirmButtonText:'确定',
                    cancelButtonText: '取消'
                }).then(() => {
                    _g.openGlobalLoading();
                    let data = {
                        'authKey': Lockr.get('authKey'),
                        'sessionId': Lockr.get('sessionId')
                    };

                    self.axios.post('http://vt.com/php/index.php/admin/users/logout', data).then(function (res) {
                        if (res.status === 200 && res.data['data'] === '退出成功') {
                            _g.closeGlobalLoading();
                            // 移除 localstorage 和 Cookies 缓存
                            Lockr.rm('authKey');
                            Lockr.rm('rememberKey');
                            Lockr.rm('userInfo');
                            Lockr.rm('sessionId');
                            Cookies.remove('rememberPwd');
                            _g.toastMsg('success', '退出成功');
                            setTimeout(() => {
                                router.replace('/');
                            }, 1500)
                        }else{
                            _g.toastMsg('error', '退出失败');
                        }
                    }).catch(function (error) {
                        if(error.status === 504){
                            bus.$message({
                                message: '请求超时，请检查网络',
                                type: 'warning'
                            })
                        }
                    })
                })
            },

            // 标签切换
            selected(name){
                this.active = name
            },
            routerChange(item) {
                // 与当前页面路由相等则刷新页面
                if (item.url !== this.$route.path) {
                    router.push(item.url)
                } else {
                    _g.shallowRefresh(this.$route.name)
                }
            }
        }
    }
</script>
<style>
    .msg-content > div{
        text-align: center;
    }
    .avatar-uploader .el-upload{
        /*border: 1px dashed #ccc;*/
        cursor: pointer;
        /*position: relative;*/
        /*top: 20px;*/
        /*left: 50px;*/
        margin-top: 20px;
        overflow: hidden;
        border-radius: 50%;

    }
    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }
    /*img样式*/
    .avatar {
        display: block;
        width: 100px;
        height: 100px;
    }
    .avatar-uploader-icon {
        font-size: 14px;
        color: #8c939d;
        width: 100px;
        height: 100px;
        line-height: 100px;
        text-align: center;
    }
    .u-name{
        margin: 5px;
        font-size: 18px;
        color: black;
    }
    .n-name{
        margin-top: 6px;
        font-size: 16px;
        color: #959595;
    }
    .level-bar{
        margin-top: 10px;
        height: 20px;
        line-height: 20px;
    }
    .level-bar span{
        /*display: inline-block;*/
        font-size: 14px;
        padding: 4px 6px;
    }
    .level-bar .score{
        position: relative;
        left: 4px;
        color: #aa5511;
        background: #fadec5;
    }
    .level-bar .level{
        color: slategray;
        background: #dcdcdc;
    }
    .detail{
        margin: 10px;
        font-size: 14px;
        color: #959595;
    }
    .detail .label{
        color: #bfbfbf;
    }
    .detail .label:last-child{
        margin-left: 8px;
    }

    .functions{
        margin: 15px;
    }
    .functions .func{
        display: inline-block;
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 50%;
        background-color: deepskyblue;
    }
    /* .functions .func-env {
        margin-left: 10px;
    } */
    .functions .count .el-badge__content.is-fixed{
        position: absolute;
        top: 0;
        right: 15px;
    }
    .logout .el-button{
        padding: 8px 8px;
        font-size: 13px;
    }

    /*目录*/
    .msg-menu{
        margin-top: 40px;
        background-color: deepskyblue;
    }
    .msg-menu > div{
        height: 50px;
        line-height: 50px;
    }
    .msg-menu a{
        display: block;
        padding-left: 53px;
        color: #ffffff;
        letter-spacing: 2px;
        /*text-align: center;*/
        font-size: 14px;
        height: 50px;
        line-height: 50px;
    }
    .msg-menu a:hover{
        /*box-shadow: 1px 1px white;*/
        border-left: 3px solid #fff;
        padding-left: 50px;
    }
    .msg-menu a > img{
        width: 20px;
        height: 20px;
        position: relative;
        top: 4px;
        right: 3px;
    }
    .msg-menu .active{
        border-left: 3px solid #fff;
        /*padding-left: 47px;*/
    }
    .msg-menu .active:hover{
        border-left: none;
    }
    .msg-menu .active a:hover{
        padding-left: 53px;
    }
</style>