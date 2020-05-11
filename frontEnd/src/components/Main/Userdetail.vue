<template>
    <div>
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>个人详情</el-breadcrumb-item>
            <el-breadcrumb-item>{{ userData.username }}</el-breadcrumb-item>
            <!-- <el-breadcrumb-item>{{ title }}</el-breadcrumb-item> -->
        </el-breadcrumb>
        <div class="part-content">
            <div class="msg-wrapper">
                <div class="main-msg">
                    <el-image :src="userData.img_url" fit="cover" alt="" class="avatar"></el-image>
                    <div class="other-side">
                        <p class="us-name">{{ userData.username }}</p>
                        <p class="ni-name">{{ userData.nickname }}</p>
                    </div>
                </div>
                <!-- <div class="tag">个人资料</div> -->
                <div class="profile">
                    <h2>基本信息</h2>
                    <div class="table-layout fixed">
                        <div>
                            <label>昵称：</label>
                            {{ userData.nickname }} </div>
                        <div>
                            <label>性别：</label>
                            {{ userData.gender }} </div>
                        <div>
                            <label>生日：</label>
                            {{ userData.birthday }} </div>
                        
                    </div>
                    <div class="table-layout fixed">
                        <div>
                            <label>邮箱地址：</label>
                            {{ userData.email }} </div>
                    </div>
                    <hr>
                    <h2>活跃概况</h2>
                    <div class="table-layout fixed">
                        <div>
                            <label>发帖数：</label>
                            {{ userData.invitations }} </div>
                        <div>
                            <label>热门数：</label>
                            {{ userData.hots }} </div>
                        <div>
                            <label>评论数：</label>
                            {{ userData.comments }} </div>
                    </div>
                    <hr class="dbl">
                    <h2>个人说明</h2>
                    <div class="table-layout fixed">
                        <div>
                            <label>负责版面：</label>
                            {{userData.duty}} </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Userdetail",
        data() {
            return {
                user_id: '',
                userData: []
            }
        },
        created() {
            
        },
        mounted() {
            // 获取用户id
            this.getParams();
            this.getUserData();
        },
        methods: {
            // 获取参数
			getParams() {
				// params 传参
				let self = this;
                self.user_id = self.$route.query.user_id;
            },
            getUserData(){
                const self = this;
                let data = {
                    "user_id": self.user_id
                }
                self.axios.post('http://vt.com/php/index.php/admin/users/getUserData', data).then(function (res) {
                    console.log(res);
                    if (res.status === 200 && res.data['error'] === '') {
                        self.userData = res.data.data;
                        console.log(self.userData);
                    }
                }).catch(function (error) {
                    if(error.status === 504){
                        bus.$message({
                            message: '请求超时，请检查网络',
                            type: 'warning'
                        })
                    }
                });
            }
        },
    }
</script>

<style scoped>
    .el-breadcrumb {
        margin: 25px 0;
        letter-spacing: 0.1em;
        font-weight: 500;
    }

    .part-content {
        min-height: 600px;
        border-top: 4px solid #00bfff;
        background-color: #ffffff;
    }

    .msg-wrapper {
        padding: 20px 35px 40px;
    }

    .main-msg {
        display: inline-block;
        width: 100%;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .other-side {
        display: inline-block;
        position: relative;
        top: -32px;
        left: 10px;
    }

    .other-side .us-name {
        margin-bottom: 5px;
        font-size: 16px;
        font-weight: 600;
        color: #535355;
    }

    .other-side .ni-name {
        font-size: 14px;
        font-weight: 500;
        color: #959595;
    }

    .avatar {
        display: inline-block;
        width: 100px;
        height: 100px;
        cursor: pointer;
        /* margin-top: 20px; */
        overflow: hidden;
        border-radius: 50%;
        text-align: center;
    }
    h2 {
        margin: 0 0 16px 0;
        font-size: 16px;
        font-weight: 600;
        color: #535353;
    }
    .profile{
        margin-top: 20px;
        font-size: 12px;
    }
    .profile label {
        font-size: 12px;
        color: #959595;
    }
    .table-layout {
        display: table;
        width: 100%;
    }
    .table-layout > * {
        display: table-cell;
        text-align: left;
        vertical-align: middle;
        padding: 4px 0;
    }
    .table-layout.fixed {
        table-layout: fixed;
    }
    hr {
        border: none;
        border-top: 1px solid #eeeeee;
        margin: 25px 0;
    }
</style>