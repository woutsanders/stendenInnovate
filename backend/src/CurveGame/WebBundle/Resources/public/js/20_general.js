/**
 * Wrapper function for sending message
 */
function sendMsg(userId, controlType) {

    return wsSupport ? wsSendMsg(userId, controlType) : ajaxSendMsg(userId, controlType);
}