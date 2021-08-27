<template>
    <div>
        <div class="mb-8 flex justify-start max-w-3xl">
            <h1 class="font-bold text-3xl">
                <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('tournaments')">Tournaments
                </inertia-link>
                <span class="text-indigo-400 font-medium">/</span> Create
            </h1>
        </div>
        <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
            <form @submit.prevent="store">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <text-input v-model="form.tourneyname" :error="form.errors.tourneyname" class="pr-6 pb-8 w-full" label="Tournament Name"/>
                    <text-input v-model="form.place" :error="form.errors.place" class="pr-6 pb-8 w-full lg:w-1/2" label="Place"/>
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">Start date & time:</label>
                        <datetime format="YYYY-MM-DD H:i" v-model="form.start_date"></datetime>
                        <div v-if="form.errors.start_date" class="form-error">{{ form.errors.start_date }}</div>
                    </div>
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">Series:</label>
                        <multiselect
                            v-model="form.series_id"
                            id="player_id"
                            label="name"
                            :show-labels="false"
                            :options="series"
                            :multiple="true"
                            placeholder="Select series"
                            track-by="id"
                            :internal-search="false"
                            :allow-empty="false"
                            :options-limit="300"
                            :limit="5"
                            @search-change="fetchSeriesData"
                        >
                            <span slot="noResult">Oops! No series found.</span>
                        </multiselect>
                        <div v-if="form.errors.series_id" class="form-error">{{ form.errors.series_id }}</div>
                    </div>
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">Structure:</label>
                        <multiselect
                            v-model="form.structure_type_id"
                            id="structure_type_id"
                            label="name"
                            :show-labels="false"
                            :options="structures"
                            placeholder="Select structure"
                            track-by="id"
                            :internal-search="false"
                            :allow-empty="false"
                            :options-limit="300"
                            :limit="5"
                            @search-change="fetchStructuresData"
                        >
                            <span slot="noResult">Oops! No structures found.</span>
                        </multiselect>
                        <div v-if="form.errors.structure_type_id" class="form-error">{{form.errors.structure_type_id}}</div>
                    </div>
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">Game:</label>
                        <multiselect
                            v-model="form.game_type_id"
                            id="game_type_id"
                            label="name"
                            :show-labels="false"
                            :options="games"
                            placeholder="Select game"
                            track-by="id"
                            :internal-search="false"
                            :allow-empty="false"
                            :options-limit="300"
                            :limit="5"
                            @search-change="fetchGamesData"
                        >
                            <span slot="noResult">Oops! No games found.</span>
                        </multiselect>
                        <div v-if="form.errors.game_type_id" class="form-error">{{ form.errors.game_type_id }}</div>
                    </div>
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">Limit:</label>
                        <multiselect
                            v-model="form.limit_type_id"
                            id="limit_type_id"
                            label="name"
                            :show-labels="false"
                            :options="limits"
                            placeholder="Select limit"
                            track-by="id"
                            :internal-search="false"
                            :allow-empty="false"
                            :options-limit="300"
                            :limit="5"
                            @search-change="fetchLimitsData"
                        >
                            <span slot="noResult">Oops! No limits found.</span>
                        </multiselect>
                        <div v-if="form.errors.limit_type_id" class="form-error">{{ form.errors.limit_type_id }}</div>
                    </div>
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">Prize:</label>
                        <multiselect
                            v-model="form.prize_id"
                            id="prize_id"
                            label="name"
                            :show-labels="false"
                            :options="prizes"
                            placeholder="Select price"
                            track-by="id"
                            :internal-search="false"
                            :allow-empty="false"
                            :options-limit="300"
                            :limit="5"
                            @search-change="fetchPrizeData"
                        >
                            <span slot="noResult">Oops! No prizes found.</span>
                        </multiselect>
                        <div v-if="form.errors.prize_id" class="form-error">{{ form.errors.prize_id }}</div>
                    </div>
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">Grade:</label>
                        <multiselect
                            v-model="form.grade_id"
                            id="grade_id"
                            label="name"
                            :show-labels="false"
                            :options="grades"
                            placeholder="Select grade"
                            track-by="id"
                            :internal-search="false"
                            :allow-empty="false"
                            :options-limit="300"
                            :limit="5"
                            @search-change="fetchGradesData"
                        >
                            <span slot="noResult">Oops! No grades found.</span>
                        </multiselect>
                        <div v-if="form.errors.grade_id" class="form-error">{{ form.errors.grade_id }}</div>
                    </div>
                    <text-input v-model="form.maxseats" :error="form.errors.maxseats" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="No. Of Sheets"/>
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">Status:</label>
                        <label class="inline-flex items-center mt-3">
                            <input type="checkbox" v-model="form.online" class="form-checkbox">
                            <span class="ml-2">Online</span>
                        </label>
                    </div>
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">List exclusion:</label>
                        <label class="inline-flex items-center mt-3">
                            <input type="checkbox" v-model="form.exclude_list" class="form-checkbox">
                            <span class="ml-2">Exclude</span>
                        </label>
                    </div>
                    <text-input v-model="form.start_chips" :error="form.errors.start_chips" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Buy-in tip"/>
                    <text-input v-model="form.entry_fee" :error="form.errors.entry_fee" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Entry fee"/>
                    <text-input v-model="form.entry_prize" :error="form.errors.entry_prize" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Entry price"/>
                    <text-input v-model="form.rebuy" :error="form.errors.rebuy" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="No. Re-buy Possible"/>
                    <text-input v-model="form.rebuy_chips" :error="form.errors.rebuy_chips" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Re-buy Tip"/>
                    <text-input v-model="form.rebuy_fee" :error="form.errors.rebuy_fee" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Re-buy Fee"/>
                    <text-input v-model="form.rebuy_prize" :error="form.errors.rebuy_prize" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Re-buy Prize"/>
                    <text-input v-model="form.addon" :error="form.errors.addon" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="No. add-ons available"/>
                    <text-input v-model="form.addon_chips" :error="form.errors.addon_chips" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Add-on chip"/>
                    <text-input v-model="form.addon_fee" :error="form.errors.addon_fee" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Add-on fee"/>
                    <text-input v-model="form.addon_prize" :error="form.errors.addon_prize" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Add-on prize"/>
                    <text-input v-model="form.reentry" :error="form.errors.reentry" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="No. reentries available"/>
                    <text-input v-model="form.reentry_chips" :error="form.errors.reentry_chips" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Reentry chip"/>
                    <text-input v-model="form.reentry_fee" :error="form.errors.reentry_fee" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Reentry fee"/>
                    <text-input v-model="form.reentry_prize" :error="form.errors.reentry_prize" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Reentry prize"/>
                    <div class="pr-6 pb-8 w-full">
                        <label class="form-label">Buying policy:</label>
                        <ckeditor v-model="form.buyin_policy" :config="config"></ckeditor>
                    </div>
                    <text-input v-model="form.url" :error="form.errors.url" class="pr-6 pb-8 w-full lg:w-1/2" type="text" label="Url"/>
                    <text-input v-model="form.entries" :error="form.errors.entries" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Number of entries"/>
                    <div class="pr-6 pb-8 w-full">
                        <label class="form-label">Details:</label>
                        <ckeditor v-model="form.comment" :config="config"></ckeditor>
                    </div>
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
                    <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="clearForm">Clear
                    </button>
                    <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Create
                        Tournament
                    </loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'
import Multiselect from "vue-multiselect"
import Datetime from 'vuejs-datetimepicker'
import CKEditor from 'ckeditor4-vue'
import axios from 'axios'

export default {
    metaInfo: {title: 'Create Tournament'},
    components: {
        LoadingButton,
        TextInput,
        Multiselect,
        Datetime,
        ckeditor: CKEditor.component,
    },
    layout: Layout,
    data() {
        return {
            form: this.$inertia.form({
                tourneyname: null,
                place: null,
                start_date: null,
                series_id: null,
                structure_type_id: null,
                game_type_id: null,
                limit_type_id: null,
                prize_id: null,
                grade_id: null,
                maxseats: '0',
                online: false,
                exclude_list: false,
                start_chips: '0',
                entry_fee: '0',
                entry_prize: '0',
                rebuy: '0',
                rebuy_chips: '0',
                rebuy_fee: '0',
                rebuy_prize: '0',
                addon: '0',
                addon_chips: '0',
                addon_fee: '0',
                addon_prize: '0',
                reentry: '0',
                reentry_chips: '0',
                reentry_fee: '0',
                reentry_prize: '0',
                buyin_policy: null,
                url: null,
                entries: '0',
                comment: null,
            }),
            config: {
                toolbar: [
                    ['Styles', 'Format', 'Font', 'FontSize', 'Bold', 'Italic', 'Underline', 'Strike', 'Undo', 'Redo', 'Subscript', 'Superscript']
                ],
                height: 200
            },
            series: [],
            structures: [],
            games: [],
            limits: [],
            prizes: [],
            grades: [],
        }
    },
    methods: {
        store() {
            this.form.transform((data) => (
                {
                    tourneyname: data.tourneyname,
                    place: data.place,
                    start_date: data.start_date,
                    series_id: data.series_id,
                    structure_type_id: data.structure_type_id ? data.structure_type_id.id : null,
                    game_type_id: data.game_type_id ? data.game_type_id.id : null,
                    limit_type_id: data.limit_type_id ? data.limit_type_id.id : null,
                    prize_id: data.prize_id ? data.prize_id.id : null,
                    grade_id: data.grade_id ? data.grade_id.id : null,
                    maxseats: data.maxseats,
                    online: data.online,
                    exclude_list: data.exclude_list,
                    start_chips: data.start_chips,
                    entry_fee: data.entry_fee,
                    entry_prize: data.entry_prize,
                    rebuy: data.rebuy,
                    rebuy_chips: data.rebuy_chips,
                    rebuy_fee: data.rebuy_fee,
                    rebuy_prize: data.rebuy_prize,
                    addon: data.addon,
                    addon_chips: data.addon_chips,
                    addon_fee: data.addon_fee,
                    addon_prize: data.addon_prize,
                    reentry: data.reentry,
                    reentry_chips: data.reentry_chips,
                    reentry_fee: data.reentry_fee,
                    reentry_prize: data.reentry_prize,
                    buyin_policy: data.buyin_policy,
                    url: data.url,
                    entries: data.entries,
                    comment: data.comment,
                }
            )).post(this.route('tournaments.store'))
        },
        clearForm() {

        },
        fetchSeriesData(search) {
            let self = this
            if (search.length > 0) {
                axios.get(this.route('pkg-series'), {params: {search: search}})
                    .then(function (response) {
                        self.series = response.data.series
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
        },
        fetchStructuresData(search) {
            let self = this
            if (search.length > 0) {
                axios.get(this.route('pkg-structures'), {params: {search: search}})
                    .then(function (response) {
                        self.structures = response.data.structures
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
        },
        fetchGamesData(search) {
            let self = this
            if (search.length > 0) {
                axios.get(this.route('pkg-games'), {params: {search: search}})
                    .then(function (response) {
                        self.games = response.data.games
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
        },
        fetchLimitsData(search) {
            let self = this
            if (search.length > 0) {
                axios.get(this.route('pkg-limits'), {params: {search: search}})
                    .then(function (response) {
                        self.limits = response.data.limits
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
        },
        fetchPrizeData(search) {
            let self = this
            if (search.length > 0) {
                axios.get(this.route('pkg-prizes'), {params: {search: search}})
                    .then(function (response) {
                        self.prizes = response.data.prizes
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
        },
        fetchGradesData(search) {
            let self = this
            if (search.length > 0) {
                axios.get(this.route('pkg-grades'), {params: {search: search}})
                    .then(function (response) {
                        self.grades = response.data.grades
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
        }
    },
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
.multiselect__option--highlight {
    background: #5661B3 !important;
    outline: none;
    color: white;
}

.multiselect__option--highlight:after {
    background: #5661B3 !important;
}

#tj-datetime-input {
    height: 41px;
    border-radius: 0.25rem;
}
</style>
