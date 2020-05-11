import Login from './components/Account/Login'
import Signup from './components/Account/Signup'
import Forgetpwd from './components/Account/Forgetpwd'
import CFirmemail from './components/Account/CFirmemail'
import Resetpwd from './components/Account/Resetpwd'
import Home from './components/Home'
import Index from './components/Main/Index'
import Articleadd from './components/Main/Articleadd'
import Article from './components/Main/Article'
import Help from './components/Main/Help'
import Userdetail from './components/Main/Userdetail'
import Part from './components/Main/Part'


/**
 * meta参数解析
 * hideLeft: 是否隐藏左侧菜单，单页菜单为true
 * module: 菜单所属模块
 * menu: 所属菜单，用于判断三级菜单是否显示高亮，如菜单列表、添加菜单、编辑菜单都是'menu'，用户列表、添加用户、编辑用户都是'user'，如此类推
 */

const routes = [
    // 登录路由，根路由，默认显示的第一个页面
    {
        path: '/',
        component: Login,
        name: 'Login',
        meta: {
            title: '莞工娜娜'
        }
    },
    // 注册路由
    {
        path: '/signup',
        component: Signup,
        name: 'Signup',
        meta: {
            title: '莞工娜娜-注册'
        }
    },
    // 忘记密码路由
    {
        path: '/forgetpwd',
        component: Forgetpwd,
        name: 'Forgetpwd',
        meta: {
            title: '莞工娜娜-填写用户名'
        }
    },
    // 确认邮箱路由
    {
        // 后面跟参数名则会显示在 URL 中，一般不需要
        path: '/cfirmemail',
        component: CFirmemail,
        name: 'CFirmemail',
        meta: {
            title: '莞工娜娜-确认注册邮箱'
        }
    },
    // 重置密码路由
    {
        path: '/resetpwd/:username',
        component: Resetpwd,
        name: 'Resetpwd',
        meta: {
            title: '莞工娜娜-重置密码'
        }
    },
    // 论坛首页
    {
        path: '/home',
        component: Home,
        // name: 'Home',
        meta: {
            title: '莞工娜娜-首页'
        },
        children: [
            // index
            {
                path: '',
                component: Index,
                name: 'Index',
                meta: {
                    title: '莞工娜娜-首页'
                },
            },
            // 添加文章版面
            {
                path: 'articleadd',
                component: Articleadd,
                name: 'Articleadd'
            },
            // 具体文章版面
            {
                path: '/article/:articleId',
                component: Article,
                name: 'Article',
                meta: {
                    title: '莞工娜娜-文章'
                }
            },
            // 具体版面
            {
                path: 'part',
                component: Part,
                name: 'Part'
            },
            // 用户详情页
            {
                path: '/userdetail',
                component: Userdetail,
                name: 'Userdetail',
                meta: {
                    title: '莞工娜娜-用户详情'
                }
            },
            // 帮助文档
            {
                path: 'help',
                component: Help,
                name: 'Help',
                meta: {
                    title: '莞工娜娜-帮助文档'
                }
            }
        ]
    },


    

];
export default routes
