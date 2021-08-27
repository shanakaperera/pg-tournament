<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Dashboard</h1>
    <FullCalendar :options="calendarOptions" />
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import '@fullcalendar/core/vdom'
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

export default {
    metaInfo: {title: 'Dashboard'},
    components: {
        FullCalendar
    },
    layout: Layout,
    data() {
        return {
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin],
                initialView: 'dayGridMonth',
                eventClick: this.handleEventClick,
                events: {
                    url: this.route('pkg-tournaments'),
                    method: 'GET',
                    failure: function() {
                        alert('there was an error while fetching events!');
                    },
                }
            }
        }
    },
    methods: {
        handleEventClick: function (info) {
            info.jsEvent.preventDefault();

            if (info.event.url) {
                window.open(info.event.url, '_blank');
            }
        }
    }
}
</script>
