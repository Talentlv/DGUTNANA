<template>
    <div>
        <!-- 轮播图 -->
        <el-carousel :interval="3000" type="card" height="300px">
            <el-carousel-item v-for="(item,index) in arrCarousel" :key="index">
                <!-- img的宽高设置为100%，不然显示有问题 -->
                <img :src="item.url" style="width: 100%;height: 100%;">
            </el-carousel-item>
        </el-carousel>
        <div class="top-block">
            <div class="block ten-hot">
                <div class="wrapper">
                    <div class="title">全校十大热门</div>
                    <ul>
                        <li v-for="(item,index) in arrTenHot"
                            :key="index"
                        >
                            <router-link :to="{name:'Article',params:{articleId:item.arId}}">{{item.title}}</router-link>
                            <div class="putTime">{{item.time}}</div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="block part-hot">
                <div class="wrapper">
                    <div class="title">
                        <div class="head">校园热点</div>
                        <!-- 更多 是个超链接，到时写 -->
                        <div class="msg-more">更多</div>
                    </div>
                    <ul>
                        <li v-for="(item,index) in arrHot"
                            :key="index"
                        >
                            <router-link :to="{name:'Article',params:{articleId:item.arId}}">{{item.title}}</router-link>
                            <div class="putTime">{{item.time}}</div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="aside">
                <div class="wrapper">
                    <div class="aside-title">热门版面</div>
                    <ul>
                        <li v-for="(item,index) in arrHotPart"
                            :key="index"
                        >
                            <router-link :to="{name:'Part',query:{type:item.type}}">{{item.title}}</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="main-block contact-block">
                <div class="title">莞工生活</div>
                <div class="content">
                    <ul>
                        <li v-for="(item,index) in arrLife"
                            :key="index"
                        >
                            <router-link :to="{name:'Part',query:{type:item.type}}">{{item.title}}
                                <span v-if="item.addCount!=0">({{item.addCount}})</span>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-block msg-block">
                <div class="title">莞工资讯</div>
                <div class="content">
                    <ul>
                        <li v-for="(item,index) in arrNews"
                            :key="index"
                        >
                            <router-link :to="{name:'Part',query:{type:item.type}}">{{item.title}}
                                <span v-if="item.addCount!=0">({{item.addCount}})</span>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-block contact-block">
                <div class="title">莞工资源</div>
                <div class="content">
                    <ul>
                        <li v-for="(item,index) in arrSource"
                            :key="index"
                        >
                            <router-link :to="{name:'Part',query:{type:item.type}}">{{item.title}}
                                <span v-if="item.addCount!=0">({{item.addCount}})</span>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Index",
        data(){
            return{
                // 轮播图
                arrCarousel: [
                    // 字符串无效，必须使用 require()函数
                    { url: require('../../assets/images/05.jpg')},
                    { url: require('../../assets/images/06.jpg')},
                    { url: require('../../assets/images/07.jpg')}
                ],
                // 十大热门
                arrTenHot: [],
                // 校园热点
                arrHot: [],
                // 热门版面
                arrHotPart: [],
                // 莞工生活
                arrLife: [],
                // 莞工资讯
                arrNews: [],
                // 莞工资源
                arrSource: [],
            }
        },
        // beforeCreate() {
        //     _g.openGlobalLoading();
        // },
        created() {
            // 获取各模块信息
            // _g.closeGlobalLoading()
            this.getAll();
        },
        methods: {
            // 获取全校十大热门
            getAll(){
                const self = this;
                self.axios.get('http://vt.com/php/index.php/admin/menus/getAllMsg').then(function (res) {
                    // console.log(res);
                    // console.log(res.data.data['updates']);
                    if (res.status === 200 && res.data['error'] === '') {
                        self.setTenHotVal(res.data.data['tenHot']);
                        self.setHotVal(res.data.data['hot']);
                        self.setHotPartVal(res.data.data['hotPart']);
                        self.setPartsVal(res.data.data['parts'],res.data.data['updates']);
                    }
                }).catch(function (err) {
                    if(err.status === 504){
						bus.$message({
							message: '请求超时，请检查网络',
							type: 'warning'
						})
					}
                })
            },
            // 获取十大热门信息
            setTenHotVal(arr){
                const self = this;
                for (let i = 0; i < arr.length; i++) {
                    // 利用 moment.js 将 2020-04-04 14:58:22 日期格式化为 YYYY-MM-DD
                    self.arrTenHot.push({arId:arr[i].article_id,title:arr[i].title,time:moment(arr[i].add_time).format('YYYY-MM-DD')});
                }
            },
            // 获取校园热点信息
            setHotVal(arr){
                const self = this;
                for (let i = 0; i < arr.length; i++) {
                    // 利用 moment.js 将 2020-04-04 14:58:22 日期格式化为 YYYY-MM-DD
                    self.arrHot.push({arId:arr[i].article_id,title:arr[i].title,time:moment(arr[i].add_time).format('YYYY-MM-DD')});
                }
            },
            
            // 获取热门版面信息
            setHotPartVal(arr){
                const self = this;
                let index = 0;
                let indexPlus = 0;
                for (let i = 0; i < arr.length; i++) {
                    if (arr[i].sort_id === 1) {         // 莞工生活
                        let typeId = arr[i].part_id;
                        this.arrHotPart.push({type:typeId,title:arr[i].name})
                    } else if (arr[i].sort_id === 2){   // 莞工资讯
                        let typeId = arr[i].part_id;
                        this.arrHotPart.push({type:typeId,title:arr[i].name})
                        index++;
                    } else if (arr[i].sort_id === 3){   // 莞工资源
                        let typeId = arr[i].part_id;
                        this.arrHotPart.push({type:typeId,title:arr[i].name});
                        indexPlus++;
                    }
                }
            },
            // 获取各版面名称和获取各版面更新帖子数量
            setPartsVal(arr,arrPlus){
                const self = this;
                let index = 0;
                let indexPlus = 0;
                for (let i = 0; i < arr.length; i++) {
                    if (arr[i].sort_id === 1) {         // 莞工生活
                        let typeId = arr[i].part_id;
                        this.arrLife.push({type:typeId,title:arr[i].name,addCount:arrPlus[arr[i].part_id]})
                    } else if (arr[i].sort_id === 2){   // 莞工资讯
                        let typeId = arr[i].part_id;
                        this.arrNews.push({type:typeId,title:arr[i].name,addCount:arrPlus[arr[i].part_id]})
                        index++;
                    } else if (arr[i].sort_id === 3){   // 莞工资源
                        let typeId = arr[i].part_id;
                        this.arrSource.push({type:typeId,title:arr[i].name,addCount:arrPlus[arr[i].part_id]});
                        indexPlus++;
                    }
                    
                }
            },
            
        },
    }
</script>

<style>
    *{
        padding: 0;
        margin: 0;
    }
    .el-carousel{
        margin-top: 15px;
    }
    .top-block .block{
        display: inline-block;
        width: 400px;
        background-color: #ffffff;
        margin: 15px 0;
        border-radius: 5px;
    }
    .top-block .ten-hot{
        border: 1px solid #abeaff;
        /*border-top: 4px solid deepskyblue;*/
    }
    .top-block .ten-hot:hover{
        box-shadow: 0 0 8px rgba(0,191,255,0.5);
        border: 1px solid #ffffff;
    }
    /* 文章发表日期 */
    .top-block .putTime{
        display: block;
        height: 25px;
        line-height: 25px;
        float: right;
        font-size: 14px;
        color: #cccccc;
    }
    .top-block .part-hot{
        margin-left: 20px;
        border: 1px solid #FF3030;
    }
    .top-block .part-hot:hover{
        box-shadow: 0 0 8px rgba(255,48,48,0.5);
        border: 1px solid #ffffff;

    }
    
    .wrapper .title{
        padding: 10px 0;
        margin: 0 12px;
        height: 20px;
        line-height: 20px;
        font-size: 16px;
        color: #262b3c;
    }
    .ten-hot .title{
        border-bottom: 2px solid deepskyblue;
    }
    .part-hot .title{
        border-bottom: 2px solid #FF3030;
    }
    .part-hot .title .head{
        display: inline-block;
        height: 20px;
        line-height: 20px;
    }
    /* 更多 */
    .part-hot .title .msg-more{
        display: block;
        height: 20px;
        line-height: 20px;
        font-size: 12px;
        float: right;
        color: #333333;
    }
    .wrapper ul{
        margin: 0;
        padding: 20px 30px;
        padding-right: 12px;
    }
    .wrapper ul li{
        height: 25px;
        line-height: 25px;
        overflow: hidden;
        /* text-overflow: ellipsis;
        white-space: nowrap; */
    }
    .wrapper ul li a{
        display: inline-block;
        width: 275px;
        height: 25px;
        line-height: 25px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #333333;
        font-size: 14px;
    }
    .wrapper ul li a:hover{
        /*color: #345cff;*/
        text-decoration: underline;
    }
    .aside{
        display: inline-block;
        width: 157px;
        background-color: #ffffff;
        border: 1px solid #dddddd;
        border-radius: 5px;
        margin-left: 20px;
    }
    .aside .wrapper .aside-title{
        padding: 10px 0;
        margin: 0 12px;
        height: 20px;
        line-height: 20px;
        font-size: 16px;
        color: #262b3c;
        border-bottom: 2px solid #dddddd;
    }
    .content .main-block{
        /*display: inline-block;*/
        /*width: 48.5%;*/
        background-color: #ffffff;
        margin: 15px 0;
        border-radius: 5px;
        vertical-align: top;
        padding: 0 15px;
    }
    /* .content .contact-block{
        /*border: 1px solid #abeaff;
    } */
    .content .msg-block{
        margin-top: 15px;
        /*border: 1px solid #FF3030;*/
    }
    .main-block .title{
        width: 65px;
        height: 20px;
        line-height: 20px;
        padding-top: 10px;
        padding-bottom: 5px;
        margin: 0;
    }
    .contact-block .title{
        border-bottom: 2px solid deepskyblue;
    }
    .msg-block .title{
        border-bottom: 2px solid #FF3030;
    }
    .main-block ul{
        /*margin-top: 8px;*/
        padding: 20px 0;
    }
    .contact-block ul li{
        border: 1px solid #abeaff;
    }
    .msg-block ul li{
        border: 1px solid #ffa1a1;
    }
    .main-block ul li{
        display: inline-block;
        border-left: none;
        /*padding: 4px 8px;*/
    }
    .contact-block ul li:first-child{
        border: 1px solid #abeaff;
    }
    .msg-block ul li:first-child{
        border: 1px solid #ffa1a1;
    }
    /*.msg-block ul li:last-child{*/
        /*border: 1px solid #ffa1a1;*/
    /*}*/
    .main-block ul li a{
        display: inline-block;
        padding: 10px 12px;
        font-size: 14px;
        color: #262b3c;
    }
    .main-block ul li a:hover span{
        color: #ffffff;
    }
    .contact-block ul li a:hover{
        background-color: deepskyblue;
        color: #ffffff;
    }
    .msg-block ul li a:hover{
        background-color: #FF3030;
        color: #ffffff;
    }
    .main-block span{
        display: inline-block;
        color:crimson
    }
    
</style>