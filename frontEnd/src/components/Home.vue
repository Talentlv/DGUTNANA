<template>
    <div>
        <div class="logo">
            <div class="logo-title">
                <span class="header">莞工娜娜</span>
                <!--<span class="d-name">nana.dgut.edu.cn</span>-->
            </div>
        </div>
        <el-row type="flex" justify="center" class="top-wrapper">
            <el-col :span="4" :offset="2">
                <div class="date-time">{{ dateTime }}</div>
            </el-col>
            <el-col :span="8" >
                <!-- <div class="search-content">
                    <el-form :model="form" :rules="rules" ref="form" label-width="0" class="searchForm">
                        <el-form-item prop="search" class="input-search">
                            <el-input type="text" v-model="form.search" @keyup.enter.native="submitForm('form')" placeholder="搜索版面、帖子、用户" auto-complete="off"></el-input>
                        </el-form-item>
                    </el-form>
                    <img src="../assets/images/search.png" alt="">
                </div> -->
            </el-col>
            <el-col :span="4" style="position: relative;">
                <!-- :to="'Help'" 无效-->
                <router-link :to="{name:'Help'}" class="help" title="点击进入帮助页面">帮助中心</router-link>
            </el-col>
        </el-row>
        <el-button type="primary" @click="showMenu = !showMenu" class="btn-menu"><i class="el-icon-menu"></i></el-button>
        <el-row class="bg-color">
            <el-col :span="3">
                <el-drawer ref="drawer" class="left-menu" title="aaa" :with-header="false" :visible.sync="showMenu" direction="ltr" size="192px" :modal="false" :show-close="true">
                    <el-button type="button" @click="showMenu = !showMenu" class="btn-close">
                        <i class="el-icon-close"></i>
                    </el-button>
                    <left-menu :menuData="menuData" ref="leftMenu"></left-menu>
                </el-drawer>
                <!--<router-link :to="{name:'Help'}">帮助中心</router-link>-->
            </el-col>
            <el-col :span="16" :offset="5" class="content">
                <router-view></router-view>
                <footer class="footer">
                    <div class="footer-menu">
                        <router-link :to="{name:'Help'}">关于娜娜</router-link> | 
                        <router-link :to="{name:'Help'}">联系我们</router-link> | 
                        <router-link :to="{name:'Help'}">帮助中心</router-link> | 
                        <router-link :to="{name:'Help'}">意见反馈</router-link> | 
                        <router-link :to="{name:'Help'}">加入我们</router-link>
                    </div>
                    <div class="copyright">
                        Copyright © 2019 - <span>
                            {{fullYear}}
                            <!-- <script>document.write(new Date().getFullYear());</script> -->
                        </span> NANA. All Rights Reserved.
                    </div>
                </footer>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import leftMenu from './Common/leftMenu'

    export default {
        name: "Home",
        data(){
            return{
                username: '',
                dateTime: '',
                menuData: [],
                form: {
                    search: ''
                },
                showMenu: false,
                // 获取当前年份
                fullYear: new Date().getFullYear()
            }
        },
        components: {
            leftMenu,
        },
        created(){
            // 动态显示当前日期和时间
            let self = this;
            moment.locale('zh-cn');     // 设置 moment 区域为中国
            setInterval(() => {
                self.dateTime = moment().format("YYYY年MM月DD日 dddd HH:mm:ss");
            },1000);

            // 检测是否登录
            self.checkLogin();

            // 获取用户信息
            self.getUserMessage();

            // console.log(self.menuData);

        },
        mounted(){

            // drawer 内容取消懒加载
            // this.$refs.drawer.open()
        },
        methods: {
            // 是否登录
            checkLogin(){
                let authkey = Lockr.get('authKey');
                let sessionId = Lockr.get('sessionId');
                if (!authkey || !sessionId){
                    _g.toastMsg('warn', '您尚未登录');
                    setTimeout(() => {
                        router.replace('/');
                    },1500)
                }
            },

            // 获取用户信息
            getUserMessage(){
                const self = this;
                self.username = Lockr.get('authKey');

                let data = {
                    'username': self.username
                };

                self.axios.post('http://vt.com/php/index.php/admin/users/getUserMsg', data).then(function (res) {
                    console.log(res);
                    if (res.status === 200 && res.data['error'] === '') {
                        self.menuData[0] = res.data['data']['username'];
                        self.menuData[1] = res.data['data']['nickname'];
                        self.menuData[2] = res.data['data']['img_url'];
                        self.menuData[3] = res.data['data']['invitations'];
                        self.menuData[4] = res.data['data']['hots'];
                        self.menuData[5] = res.data['data']['Id'];
                        
                    }else {
                        _g.toastMsg('warn', '获取用户信息失败')
                    }
                }).catch(function (error) {
                    if(error.status === 504){
                        bus.$message({
                            message: '请求超时，请检查网络',
                            type: 'warning'
                        })
                    }
                });

                console.log(self.menuData);
            }
        }
    }
</script>

<style>
    .bg-color{
        background-color: #f9f7fb;
    }
    .logo{
        width: 192px;
        height: 50px;
        position: fixed;
        top: 0;
        left: 0;
        text-align: center;
        background: #00BFFF;
        z-index: 999;
    }
    .logo .logo-title{
        height: 50px;
        line-height: 50px;
    }
    .logo .logo-title span{
        display: block;
        margin: 0 auto;
        color: #ffffff;
    }
    .logo-title .header{
        font-size: 24px;
        font-weight: 600;
        letter-spacing: 3px;
        text-shadow: 1px 0 4px rgba(255, 255, 255, 0.75);
    }
    /*.logo-title .d-name{*/
        /*font-size: 12px;*/
        /*font-weight: 400;*/
        /*text-shadow: 1px 0 4px rgba(255, 255, 255, 0.75);*/
    /*}*/
    .top-wrapper{
        height: 50px;
        line-height: 50px;
        background-color: #eeeeee;
    }
    .date-time{
        height: 50px;
        line-height: 50px;
        font-size: 14px;
        color: #959595;
        /*margin-left: 2px;*/
    }
    .search-content{
        height: 50px;
        text-align: center;
    }
    .search-content .searchForm{
        display: inline-block;
        width: 296px;
        margin-left: 168px;
    }
    .searchForm .input-search{
        margin-bottom: 0;
    }
    .input-search .el-input__inner{
        height: 35px;
        line-height: 35px;
        padding: 0 10px;
    }
    .input-search .el-form-item__content{
        line-height: 35px;
    }
    .search-content>img{
        display: inline-block;
        width: 15px;
        height: 15px;
        position: relative;
        left: -25px;
        top: 2px;
    }
    .help{
        display: inline-block;
        color: #959595;
        position: absolute;
        right: 0;
    }

    /*左侧菜单*/
    .left-menu{
        width: 192px;
        height: 100%;
        position: fixed;
        top: 50px;
        left: 0;
    }
    /*去除 focus 边框*/
    .left-menu:focus{
        outline: none !important;
    }
    .left-menu .el-drawer:focus{
        outline: none!important;
    }
    .left-menu .el-drawer__body:focus{
        outline: none !important;
    }
    .btn-menu{
        position: fixed;
        left: -20px;
        padding-left: 21px;
        padding-right: 3px;
        z-index: 999;
    }
    /*重写背景*/
    .left-menu .el-drawer__body{
        background-color: #9fdaff2e;
    }
    /*关闭左侧菜单按钮*/
    .btn-close{
        padding: 1px 6px;
        position: absolute;
        top: 0;
        right: 0;
    }

    /* 底部菜单 */
    .footer{
        margin-top: 100px;
        font-size: 12px;
        color: #666;
        padding: 25px 0;
        text-align: center;
    }
    /* .footer-menu{
    } */
    .footer-menu > a{
        display: inline-block;
        height: 30px;
        line-height: 30px;
        color: #666;
    }
    .footer-menu > a:hover{
        color: #00BFFF;
    }
</style>