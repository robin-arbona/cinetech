const app = Vue.createApp({
    data() {
        return {
            type: '',
            id: 0,
            credits: {},
            reviews: {},
            overview: {},
            commentarySql: {},
            favMovies: false,
            favTv: false,
            apiSession: {},
            fav: '',
            replyComment: "",
        }
    },
    computed: {
        basePath() {
            return document.querySelector('#conf>input[name=base_path]').getAttribute('value')
        },
        httpHost() {
            return 'http://' + document.querySelector('#conf>input[name=http_host]').getAttribute('value')
        },
        emptyFavorite() {
            let areEmpty = true
            if (this.favMovies && this.favTv) {
                areEmpty = (this.favMovies.results.length > 0 || this.favTv.results.length) > 0 ? false : true
            }
            return areEmpty
        }
    },
    watch: {
        id: function () {
            this.get('credits')
            this.get('reviews')
            this.get('overview')
            this.getReviewSql();
        },
        favTv: function () {
            if ((this.favMovies && this.favTv)) {
                this.fav = this.isFav()
            }
        },
        favMovies: function () {
            if ((this.favMovies && this.favTv)) {
                this.fav = this.isFav()
            }
        },
        overview: function () {
            this.overview.title = typeof this.overview.title !== 'undefined' ? this.overview.title : this.overview.name
        }
    },
    methods: {
        changePage: function (obj) {

            let param = typeof obj.id === 'undefined' ? obj.keyword : obj.id
            let url = this.basePath + '/' + obj.page + '/' + param
            window.location.assign(encodeURI(url))
        },
        getPrgInfo: function () {
            if (document.querySelector('#prg-info > input[name=type]') !== null) {
                this.type = document.querySelector('#prg-info > input[name=type]').getAttribute('value')
                this.id = document.querySelector('#prg-info > input[name=id]').getAttribute('value')
            }
        },
        get: function (info) {
            let urlRequest = api.base + '/' + this.type + '/' + this.id + '/' + info + api.key
            urlRequest = (info == 'overview') ? api.base + '/' + this.type + '/' + this.id + api.key : urlRequest
            this.getRequestApi(urlRequest, info)
        },
        getRequestApi: function (urlRequest, store) {
            fetch(urlRequest)
                .then(response => response.json())
                .then(json => this[store] = json)

        },
        getReviewSql: function () {
            let url =  this.basePath + '/review/' + this.type + "/" + this.id
            fetch(url).then(response => response.json()).then(json => this['commentarySql'] = json)
        },
        getReviewReplySql: function () {
            let url = + this.basePath + '/review/' + this.type + "/" + this.id
            fetch(url).then(response => response.json()).then(json => this['commentarySql'] = json)
                .then(json => {
                    this[store] = json
                })
        },
        apiConnect: async function () {
            let urlRequest = api.base + '/authentication/token/new' + api.key
            let token = await fetch(urlRequest).then(response => response.json())
            if (!token.success) {
                console.log('Token request failed', token)
                return
            } else {
                urlRequest = this.httpHost + this.basePath + '/token/set'
                reponse = await this.postFormData(urlRequest, { "token": token.request_token })
                console.log(reponse)
            }
            urlRequest = 'https://www.themoviedb.org/authenticate/' + token.request_token + '?redirect_to=' + this.httpHost + this.basePath;
            window.location.assign(urlRequest)
        },
        getSession: async function () {
            let urlRequest = this.httpHost + this.basePath + '/session/get'
            let session = await fetch(urlRequest).then(reponse => reponse.json())
            if (session.session.api_session.length > 0) {
                this.apiSession.session_id = session.session.api_session
                this.getFav()
                console.log('Session already started', session)
                return
            }

            urlRequest = this.httpHost + this.basePath + '/token/get'
            let token = await fetch(urlRequest).then(reponse => reponse.json())
            if (!token.success || token.token === "" || token.token === "undefined") {
                return console.log('No token available', token)
            }
            urlRequest = 'https://api.themoviedb.org/3/authentication/session/new' + api.key + '&request_token=' + token.token
            session = await fetch(urlRequest).then(reponse => reponse.json())
            if (!session.success) {
                return console.log('No session return by api', session)
            }

            urlRequest = this.httpHost + this.basePath + '/session/set'
            this.postFormData(urlRequest, { 'session': session.session_id })

            urlRequest = this.httpHost + this.basePath + '/token/set'
            reponse = this.postFormData(urlRequest, { "token": token.token })

            this.getFav()
        },
        postFormData: async function (url, dataSet) {
            let form = new FormData()
            for (const key in dataSet) {
                if (dataSet.hasOwnProperty.call(dataSet, key)) {
                    const element = dataSet[key];
                    form.append(key, element)
                }
            }
            let response = await fetch(url, {
                method: 'POST',
                body: form
            }).then(response => response.json())
            return response
        },
        toggleFav: function () {
            console.log('toggle')
            this.setFav(this.type, this.id, !this.isFav())
        },
        getFav: async function (type) {
            if (typeof this.apiSession.session_id === 'undefined') {
                return
            }

            let urlRequest = api.base + '/account/%7Baccount_id%7D/favorite/movies' + api.key + '&session_id=' + this.apiSession.session_id + '&language=en-US&sort_by=created_at.asc&page=1'
            this.getRequestApi(urlRequest, 'favMovies')

            urlRequest = api.base + '/account/%7Baccount_id%7D/favorite/tv' + api.key + '&session_id=' + this.apiSession.session_id + '&language=en-US&sort_by=created_at.asc&page=1'
            this.getRequestApi(urlRequest, 'favTv')
        },
        setFav: function (type, id, state) {
            if (typeof this.apiSession.session_id === 'undefined') {
                return
            }
            let urlRequest = api.base + '/account/%7Baccount_id%7D/favorite' + api.key + '&session_id=' + this.apiSession.session_id
            let fav = {
                "media_type": type,
                "media_id": id,
                "favorite": state
            }
            fetch(urlRequest, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(fav),
                method: 'POST',
            }).then(reponse => reponse.json()).then(json => {
                console.log(json)
                this.favMovies = false
                this.favTv = false
                this.getFav()
            })
        },
        isFav: function () {
            let isFav = false
            if (this.type == 'movie') {
                this.favMovies.results.forEach(fav => {
                    if (fav.id == this.id) {
                        isFav = true
                    }
                })
            } else {
                this.favTv.results.forEach(fav => {
                    if (fav.id == this.id) {
                        isFav = true
                    }
                })
            }
            return isFav
        },
        addReply(event) {
            this.replyComment = event.id_comment;
            console.log(this.replyComment)
        },
        addComment: async function () {
            console.log(this.replyComment)
            let urlRequest = this.basePath + '/review/new/' + this.type + "/" + this.id
            let commentary = document.querySelector('#writeComment').value
            reponse = await this.postFormData(urlRequest, { "replyComment": this.replyComment, "commentary": commentary })
            console.log(reponse)
            this.getReviewSql()


        },



    },
    mounted() {
        this.getPrgInfo()
        this.getSession()
    }
})

app.component('api-connect', {
    template:
        `<button class='btn btn-primary' @click="$emit('apiConnect')">Connect Api</button>`
})

app.component('search-modul', {
    data() {
        return {
            query: '',
            dataList: {},
            results: {},
            picLowQual: api.picLowQual
        }
    },
    props: ['keywords'],
    computed: {
        showResults() {
            return this.keywords.length > 0 ? true : false
        },
    },
    watch: {
        query: function () {
            this.getDataList()
        }
    },
    methods: {
        getDataList: function () {
            let urlRequest = api.base + '/' + 'search/multi' + api.key + '&query=' + this.query
            this.getRequestApi(urlRequest, 'dataList')
        },
        getResults: function () {
            let urlRequest = api.base + '/' + 'search/multi' + api.key + '&query=' + this.keywords
            this.getRequestApi(urlRequest, 'results')
        },
        getRequestApi: function (urlRequest, store) {
            fetch(urlRequest)
                .then(response => response.json())
                .then(json => this[store] = json)
        },
        title: function (data) {
            switch (data.media_type) {
                case 'movie':
                    return data.title
                    break;
                case 'tv':
                    return data.name
                    break;
                default:
                    break;
            }
        },
        showResult(result) {
            return (result.media_type === 'tv' || result.media_type === 'movie') && result.poster_path !== null ? true : false;
        }
    },
    mounted() {
        if (this.showResults) {
            this.getResults()
        }
    },
    template:
        `<div>
        <teleport to="#searchBarPlaceHolder">
            <form @submit.prevent="$emit('changePage',{ page :'search', keyword:query})" class="d-flex">   
                <input class="form-control me-2"  placeholder="Search" type="search" list="keywords" v-model="query" />
                <datalist id="keywords">
                    <option v-for="result in dataList.results" :value="title(result)" />

                </datalist>
                <button class="btn btn-outline-danger" type="submit">Search</button>
            </form>
        </teleport>
        <div v-if="showResults" >
            <h2>Results for : {{keywords}}</h2>
            <div class="result" @click="$emit('changePage',{page:result.media_type , id:result.id})" v-for="result in results.results">
                <div v-if="showResult(result)">
                    <img class="result__img" v-bind:src="picLowQual + result.poster_path">
                    <div class="result__content">
                        <h3>{{title(result)}} <span class="result__type">type: {{result.media_type }}</span></h3> 
                        <p>{{result.overview}}</p>
                    </div>
                    <div class="result__clear"></div>
                </div>
            </div>
        </div>
    </div>
    `
})

app.component('prg-overview', {
    data() {
        return {
            picHighQual: api.picHighQual,
        }
    },
    computed: {
        img_path() {
            return (this.info.backdrop_path != null) ? this.info.backdrop_path : this.info.poster_path
        },
        heart() {
            return {
                'heart': this.fav !== '' ? true : false,
                'heart--red': this.fav === true ? true : false,
                'heart--black': this.fav === false ? true : false,
            }
        }
    },
    props: ["info", "fav"],
    template:
        `<div>
            <div class="prg_info">
                    <img class="prg_info__img" v-bind:src="picHighQual + img_path" >
                    <div class="prg_info__content">
                        <h3 class="prg_info__title">{{ info.title }}</h3>  
                        <p class="prg_info__overview">{{ info.overview }}</p>
                        </div>
                    <div class="prg_info__favorite">
                        <div :class="heart" @click="$emit('toggleFav')"></div>
                    </div>
                    <p class="prg_info__note"> {{ info.vote_average }} / 10</p>
            </div>   
        </div>`
})

app.component('prg-review', {

    props: ['review'],
    computed: {

        imgAvatar() {
            let nonePicPath = 'https://static.wixstatic.com/media/109580_c3da31ed06484c7e8e225c46beecd507~mv2.png/v1/fill/w_220,h_220,al_c,q_85,usm_0.66_1.00_0.01/avatar%20neutre.webp'

            if (typeof this.review.author_details == 'undefined') {

                return this.review.image
            }

            let picPath = this.review.author_details.avatar_path === null ? nonePicPath : this.review.author_details.avatar_path.substring(1)
            return picPath.match(/^http/gmi) ? picPath : api.picLowQual + picPath
        },
        rating() {
            if (typeof this.review.author_details != 'undefined')
                return this.review.author_details.rating + '/10'
            else {
                return ""
            }
        },
        isApi() {
            return { 'summary_review_api': (typeof this.review.author_details == 'undefined') ? false : true }
        },
        isReply() {
            return { 'review_reply': (typeof this.review.author_details == 'undefined') && (typeof this.review.id_commentaire == 'undefined') ? true : false }
        },
        isReply_input() {
            if ((typeof this.review.author_details == 'undefined') && (typeof this.review.id_commentaire !== 'undefined')) {
                return true;
            }
            else {
                return false;
            }
        },
        dateFormat() {
            if (typeof this.review.author_details == 'undefined') {

                return this.review.created_at.substring(0, this.review.created_at.length - 3)
            }
            else {
                return this.review.created_at.replace('T', ' ').substring(0, this.review.created_at.length - 8);

            }
        },

    },

    template:
        `<details class="prg-review" :class='isReply'>
            <summary  class="prg-review__summary" :class='isApi' :class='isReply'>{{review.author}} 
                <span class="prg-review__note">({{rating}})</span>
                <button type="button" v-if='isReply_input' @click='$emit("addReply",{id_comment : review.id_commentaire})' 
                    :value="review.id_commentaire">Repondre
                </button>
                </summary>
                <img class="prg-review__avatar" v-bind:src="imgAvatar">
                <p class="prg-review__content" > {{review.content}}</p>
                <span class="date_format">{{dateFormat}}</span>                
                
        <div class="prg-review__clear"></div>
    </details>`
})

app.component('prg-casting', {
    props: ['actor'],
    template:
        `<div class="prg-actor">
        <img v-if="actor.profile_path" class="prg-actor__img" v-bind:src="\'https://image.tmdb.org/t/p/w500//\'+ actor.profile_path">
        <img v-else class="prg-actor__img" height="400" width="200" src="https://pukt.pl/wp-content/uploads/2019/12/YPS__human_avatar_portrait_photography_picture_photo-512-300x300.png">
        <p class="prg-actor__name">{{actor.name}} as {{actor.character}}</p>
    </div>`
})

app.component('carrousel-custom', {
    data() {
        return {
            results: {}
        }
    },
    props: ['size', 'request', 'filter'],
    methods: {
        getData: function () {
            let url = api.base + this.request + api.key + this.filter
            fetch(url)
                .then(response => response.json())
                .then(json => this.results = json.results)
        }
    },
    mounted() {
        this.getData()
    },
    template:
        `<div class="carrousel" >
            <carrousel-item @change-page="$emit('changePage',$event)" class="carrousel__item" v-for="result in results"  :result="result" :size="size" :key="result.id"></carrousel-item>
        </div>`
})

app.component('carrousel-item', {
    data() {
        return {
            show: false,
            picLowQual: api.picLowQual
        }
    },
    props: ['result', 'size'],
    computed: {
        classSizeModifObj() {
            return {
                'car_item__img--big': this.size === 'big' ? true : false,
                'car_item__img--medium': this.size === 'medium' ? true : false,
                'car_item__img--small': this.size === 'small' ? true : false
            }
        }
    },
    methods: {
        toggleVisibility() {
            this.show = this.show === true ? false : true;
        },
    },
    template:
        `<div v-if="result.poster_path !== null" >
            <img @click="toggleVisibility" class="car_item__img" :class="classSizeModifObj" v-bind:src="picLowQual + result.poster_path" >
            <modal-custom @closeModal="toggleVisibility" @change-page="$emit('changePage',$event)" v-if="show" :result="result"></modal-custom>
        </div>`
})

app.component('modal-custom', {
    data() {
        return {
            picHighQual: api.picHighQual,
            type: '',
            id: this.result.id
        }
    },
    props: ['result'],
    computed: {
        img_path() {
            return (this.result.backdrop_path != null) ? this.result.backdrop_path : this.result.poster_path
        }
    },
    methods: {
        titleHandeling() {
            this.type = typeof this.result.title !== 'undefined' ? 'movie' : 'tv'
            this.result.title = typeof this.result.title !== 'undefined' ? this.result.title : this.result.name
        }
    },
    mounted() {
        this.titleHandeling()
    },
    template:
        `<div class="modal">
            <button @click="$emit('closeModal')" class="modal__close_btn">X</button>
            <img class="modal__photo" v-bind:src="picHighQual + img_path" >
            <div class="modal__content">
                <h3>{{ result.title }}</h3>
                <p>Overview : {{ result.overview }}</p>
                <p>Vote : {{ result.vote_average }}</p>
                <button @click="$emit('changePage', { page : type, id : id })">More information</button>
            </div>
        </div>`
})

app.component('favorite-list', {
    data() {
        return {
            picLowQual: api.picLowQual,
        }
    },
    props: ['favList'],
    emits: ['changePage'],
    methods: {
        type(result) {
            return typeof result.title !== 'undefined' ? 'movie' : 'tv'
        },
        title(result) {
            return typeof result.title !== 'undefined' ? result.title : result.name
        }
    },
    template:
        `
    <div class="result" @click="$emit('changePage',{page: type(result) , id:result.id})" v-for="result in favList.results">
        <img class="result__img" v-bind:src="picLowQual + result.poster_path">
        <div class="result__content">
            <h3>{{title(result)}} <span class="result__type">type: {{ type(result) }}</span></h3> 
            <p>{{result.overview}}</p>
        </div>
        <div class="result__clear"></div>
    </div>
    `

})

const vm = app.mount('#app')
