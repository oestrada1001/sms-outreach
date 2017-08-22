var home = new Tour({
    steps: [
        {
            element: "#dashboard",
            title: "Dashboard Overview",
            content: "The dashboard contains tables that will show you the number of visiters and the number of new subscribers.",
            reflex: true
        },
        {
            placement: "bottom",
            element: "#checkin_table",
            title: "Check-in Table",
            content: "The Check-in table is dedicated to show you the number of returning customers(visitors) that check-in.<br><br><b>Note:</b> This will keep track of the visitors as long as you have a <i>Visit Achievement Message</i> set."
        },
        {
            placement: "top",
            element: "#subscription_table",
            title: "Subscription Table",
            content: "This table is dedicated to show you the number of new subscibers."
        },
        {
            element: ".treeview",
            title: "SMS Messages",
            content: "In this tab you will be able to view/delete and edit your text messages and their corresponding dates.<br><br><b>Important:</b> Please be sure to set-up your messages before collecting subscribers."
        },
        {
            element: "#database",
            title: "Database",
            content: "The database contains all your subscribers and their information."
        },
        {
            element: "#settings",
            title: "Settings",
            content: "The settings contains your information as well as other options."
        },
        {
            placement: "left",
            element: ".fa-file-text",
            title: "Subscription Form",
            content: "To start collecting subscribers open the subscription form. Your customers will not be able to logout or leave the subscription form without verifying your password."
        }
]});


var set_sms = new Tour({
    steps: [
        {
            placement: "bottom",
            element: ".default_message",
            title: "Setting your Default Message",
            content: "This message will only be sent only to new subscribers. If not this message is not set, by default it will be a <i>Thank You</i> message.",
            reflex: true
        },
        {
            placement: "top",
            element: ".custom_message",
            title: "Scheduling your Custom Message",
            content: "Schedule your Custom Message to go out whenever it benefits you. Whether it is promotions, events, special offers, or anything else.",
            reflex: true
        },
        {
            placement: "top",
            element: ".inactive_message",
            title: "Setting your Inactive Message",
            content: "Your Inactive Message will be sent out to your <i>subscribers</i> if they have not <i>checked-in</i> in the given amount of days you set.<br><br><b>Note:</b> In order for this to work your <i>Visit Achievement Message</i> must be set as well.",
            reflex: true
        },
        {
            placement: "top",
            element: ".visit_message",
            title: "Setting your Visit Achievement Message",
            content: "Your Visit Achievement Message will only be sent to your subscribers that check-in in the amount of times you set.",
            reflex: true
        }
    ]
});
        











