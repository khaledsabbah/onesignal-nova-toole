<template>
    <div>
        <heading class="mb-6">Onesignalnotificationhistory</heading>
            <table cellpadding="0" cellspacing="0" data-testid="resource-table" class="table w-full">
                <tr>
                    <th>Heading</th>
                    <th>Content</th>
                    <th>android successful</th>
                    <th>android failed</th>
                    <th>ios successful</th>
                    <th>ios failed</th>
                    <th>Time</th>
                </tr>
                <tr v-for="notification in notifications">
                    <td><a v-bind:href="'/admin/v1/OneSignalNotificationHistoryDetails/'+ notification.id">{{ notification.headings.en }}</a></td>
                    <td>{{ notification.contents.en }}</td>
                    <td>{{  notification.platform_delivery_stats.android ? notification.platform_delivery_stats.android.successful : ''}}</td>
                    <td>{{  notification.platform_delivery_stats.android ? notification.platform_delivery_stats.android.failed : ''}}</td>
                    <td>{{  notification.platform_delivery_stats.ios ? notification.platform_delivery_stats.ios.failed : '' }}</td>
                    <td>{{  notification.platform_delivery_stats.ios ? notification.platform_delivery_stats.ios.successful : ''}}</td>
                    <td>{{ formatDateFromTimestamp(notification.completed_at) }}</td>
                </tr>
            </table>
    </div>
</template>

<script>
export default {

    props: ['notifications'],

    mounted() {
            this.getData().then(res => res.json())
            .then(data => {
                console.log(data.notifications)
                this.notifications = data.notifications
            })
            .catch((error) => {
                this.notifications = [];
                alert("Something went wrong");
                console.log(error);
            });
    },

    methods: {
        async getData() {
            const response = await fetch(`https://onesignal.com/api/v1/notifications?app_id=${process.env.MIX_ONE_SIGNAL_APP_ID}&limit=50&offset=0&kind=0`, {
                method: 'GET', // *GET, POST, PUT, DELETE, etc.
                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                headers: {
                    'Content-Type'  : 'application/json',
                    'Authorization' : `Basic ${process.env.MIX_ONE_SIGNAL_AUTHORIZATION_TOKEN}`,
                },
            });

            return await response; // parses JSON response into native JavaScript objects
        },
        formatDateFromTimestamp(timestamp){
            return moment.unix(timestamp).format('MM-DD-YYYY h:m:s')
        }
    }
}
</script>

<style>
/* Scoped Styles */
</style>
