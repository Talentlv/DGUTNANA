<template>
    <div>
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>{{ sortName }}</el-breadcrumb-item>
            <el-breadcrumb-item>{{ partName }}</el-breadcrumb-item>
            <!-- <el-breadcrumb-item>{{ title }}</el-breadcrumb-item> -->
        </el-breadcrumb>
        <div class="part-content">
            <div class="top-head">
                <header>{{ partName }}</header>
                <div class="message">
                    今日：<span>{{ dayUps }}</span>
                    帖数：<span>{{ sumTies }}</span>
                </div>
                <div class="desc">{{ desc }}</div>
                <div class="duty">版主：<span>{{ duty }}</span></div>
            </div>
            <div class="main-part">
                <div class="list-title">
                    <span>标题</span>
                    <span>作者</span>
                    <span>评论</span>
                    <!-- <span>标题</span> -->
                </div>
                <div class="list-content">
                    <!-- 在此处开始循环 -->
                    <div v-for="(item,i) in nowData" :key="i" class="list-item">
                        <div class="item-title limit">
                            <router-link :to="{name:'Article',params:{articleId:item.article_id}}">{{ item.title }}</router-link>
                        </div>
                        <!-- <div class="item-avatar">
                            <el-image :src="img_url" fit="cover" alt="" class="avatar"></el-image>
                        </div> -->
                        <div class="item-author">
                            <div class="name limit">{{ item.author }}</div>
                            <div class="time">{{ item.add_time }}</div>
                        </div>
                        <div class="item-comments">{{ item.post_num }}</div>
                    </div>
                </div>
            </div>
            <el-pagination
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :current-page="currentPage"
                :page-sizes="[10, 15, 20]"
                :page-size="pageSize"
                layout="total, prev, pager, next, jumper"
                :total="total">
            </el-pagination>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Part",
        data() {
            return {
                // 版面id
                part_id: '',
                // 模块名
                sortName: '',
                // 版面名
                partName: '',
                desc: '',
                duty: '',
                // 今日更新
                dayUps: '',
                // 总帖数
                sumTies: '',
                // 版面所有文章数据
                articleData: [],
                
                // 当前页
                currentPage: 1,
                // 每页显示条数
                pageSize: 10,
                // 所有页面的数据
                totalPage: [],
                // 当前页面的数据
                nowData: [],
                // 总条目数
                total: 1,
                // 总页数
                pagesNum: 1
            }
        },

        created() {
            this.getParams();
            this.getPartName();
            this.getPartMsg();
            this.getArticleData();
        },
        methods: {
            // 获取参数
			getParams() {
				// params 传参
				let self = this;
                self.part_id = self.$route.query.type;
                // console.log(self.articleId)
				// 如果是 query 传参，那就是this.$route.query.email
            },
            // 获取文章所属版面/模块
            getPartName(){
                // 区分 this
                const self = this;
                let data = {
                    "part_id": self.part_id
                }
                self.axios.post('http://vt.com/php/index.php/admin/articles/getArticlePart', data).then(function (res) {
                    // console.log(res);
                    if (res.status === 200 && res.data['error'] === '') {
                        self.sortName = res.data.data['sortName'];
                        self.partName = res.data.data['partName'];
                    }
                }).catch(function (error) {
                    if(error.status === 504){
                        bus.$message({
                            message: '请求超时，请检查网络',
                            type: 'warning'
                        })
                    }
                });
            },
            getPartMsg(){
                // 区分 this
                const self = this;
                let data = {
                    "part_id": self.part_id
                }
                self.axios.post('http://vt.com/php/index.php/admin/articles/getPartMsg', data).then(function (res) {
                    // console.log(res);
                    if (res.status === 200 && res.data['error'] === '') {
                        self.desc = res.data.data['desc'];
                        self.duty = res.data.data['duty'];
                        self.dayUps = res.data.data['updates'];
                        self.sumTies = res.data.data['sumTies'];
                    }
                }).catch(function (error) {
                    if(error.status === 504){
                        bus.$message({
                            message: '请求超时，请检查网络',
                            type: 'warning'
                        })
                    }
                });
            },
            getArticleData(){
                // 区分 this
                const self = this;
                let data = {
                    "part_id": self.part_id
                }
                self.axios.post('http://vt.com/php/index.php/admin/articles/getArticleData', data).then(function (res) {
                    // console.log(res);
                    if (res.status === 200 && res.data['error'] === '') {
                        // console.log(res.data.data);
                        self.articleData = res.data.data;
                        // 获取总条目数
                        self.total = self.articleData.length;
                        // 获取总页数
                        self.pagesNum = Math.ceil(self.articleData.length/self.pageSize) || 1;
                        // 生成每页显示的数据
                        self.setPagesData(self.articleData);
                    }
                }).catch(function (error) {
                    if(error.status === 504){
                        bus.$message({
                            message: '请求超时，请检查网络',
                            type: 'warning'
                        })
                    }
                });
            },
            setPagesData(arr){
                const self = this;
                for (let i = 0; i < self.pagesNum; i++) {
                    // 每一页都是一个数组 形如 [['第一页的数据'],['第二页的数据'],['第三页数据']]
                    // 根据每页显示数量 将后台的数据分割到 每一页,假设pageSize为5， 则第一页是1-5(对slice操作来说是0-5)条，第二页是6-10(对slice操作来说是5-10)条...
                    self.totalPage[i] = arr.slice(self.pageSize * i, self.pageSize * (i + 1));
                }
                // 加载时初始化第一页数据
                self.nowData = self.totalPage[0];
                // console.log(self.totalPage);
            },
            // 分页内容
            handleSizeChange(val) {
                // this.pageSize = val;
            },
            handleCurrentChange(val) {
                const self = this;
                self.nowData = self.totalPage[val-1];

                // console.log(self.nowData);
            }
        },
    }
</script>

<style scoped>
    .el-breadcrumb{
        margin: 25px 0;
        letter-spacing: 0.1em;
        /* font-weight: 500; */
    }
    .part-content{
        min-height: 600px;
        border-top: 4px solid #00bfff;
        background-color: #ffffff;
    }
    .top-head{
        padding: 25px;
        padding-left: 30px;
        font-size: 14px;
        color: #959595;
        border-bottom: 1px solid #eeeeee;
    }
    .top-head header{
        margin-bottom: 15px;
        color: black;
        font-size: 26px;
        letter-spacing: 0.1em;
    }
    .top-head > div{
        padding-bottom: 8px;
    }
    .top-head span{
        display: inline-block;
        margin-right: 10px;
        color:crimson;
    }
    .main-part{
        min-height: 564px;
        padding: 15px;
        padding-left: 30px;
    }
    .main-part .list-title{
        position: relative;
    }
    .list-title > span{
        display: inline-block;
        font-size: 12px;
        color: #959595;
    }
    .list-title > span:first-child{
        margin-left: 8px;
    }
    .list-title > span:last-child{
        float: right;
        margin-right: 20px;
    }
    .list-title > span:nth-child(2){
        position: absolute;
        right: 180px;
    }
    .list-content{
        margin: 15px 0;
        margin-bottom: 0;
    }
    .list-item{
        height: 50px;
        position: relative;
        border-radius: 6px;
    }
    .list-item:nth-child(2n+1){
        background-color: #f7f9fb;
    }
    .list-item:hover{
        background-color: #d7f5ff;
    }
    .list-item > div{
        display: inline-block;
        height: 50px;
        line-height: 50px;
    }
    .list-item .limit{
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
    .list-item .item-title{
        width: 500px;
        font-size: 14px;
        margin-left: 8px;
    }
    .list-item .item-title a{
        display: block;
        width: inherit;;
        color: black;
    }
    .list-item .item-comments{
        width: 30px;
        float: right;
        margin-right: 12px;
        font-size: 12px;
    }
    .list-item .item-author{
        position: absolute;
        right: 120px;
    }
    .list-item .item-author > div{
        line-height:normal;
        font-size: 12px;
    }
    .list-item .item-author .name{
        position: absolute;
        width: 70px;
        top: 8px;
        right: 15px;
    }
    .list-item .item-author .time{
        position: absolute;
        width: 100px;
        bottom: 8px;
        right: -15px;
    }
    .list-item .item-avatar{
        position: absolute;
        right: 210px;
    }


    .item-avatar .avatar{
        display: inline-block;
        width: 40px;
        height: 40px;
        cursor: pointer;
        /* margin-top: 20px; */
        overflow: hidden;
        border-radius: 50%;
        text-align: center;
        vertical-align: middle;
    }
    /* .el-image__error{
        font-size: 10px;
    } */
    /* 分页 */
    .el-pagination{
        padding-bottom: 18px;
        text-align: center;
    }
</style>