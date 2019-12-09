Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'OneSignalNotificationHistory',
            path: '/OneSignalNotificationHistory',
            component: require('./components/Tool'),
        },
        {
            name: 'OneSignalNotificationHistoryDetails',
            path: '/OneSignalNotificationHistoryDetails/:id',
            component: require('./components/HistoryDetails'),
        },
    ])
})
