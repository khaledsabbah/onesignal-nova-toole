<template>
    <div>
        <heading class="mb-6">Onesignalnotificationhistory</heading>
            <table cellpadding="0" cellspacing="0" data-testid="resource-table" class="table w-full">
                <tr>
                    <th>User</th>
                    <th>Name</th>
                </tr>
                <tr v-for="device in devices">
                    <td>{{ device.users[0] ? device.users[0].username : '' }}</td>
                    <td>{{ device.users[0] ? device.users[0].name : '' }}</td>
                </tr>
            </table>
    </div>
</template>

<script>
export default {

    props: ['notificationId','devices'],

    mounted() {
            this.notificationId = this.$route.params.id;
            axios.get(`/nova-vendor/OneSignalNotificationHistory/notification-history-details/${this.notificationId}`)
            .then(response => {
                this.devices = response.data.devices;
            })
            .catch((error) => {
                this.devices = [];
                alert("Something went wrong");
            });
    }
}
</script>

<style>
/* Scoped Styles */
</style>
