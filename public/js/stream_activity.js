jQuery(document).ready(function($) {
    var client = stream.connect('nbwsnpgh48zb', null, '24049');
    //var user1 = client.feed('user', '17', 'I3Z1eBEC2vuDmxbFomOdW9MxPss');
    var user1 = client.feed('user', '17', '8zJCILjsOx4QRoLsMk41ySMvJaU');

    function callback(data) {
        console.log(data);
    }

    function successCallback() {
        console.log('now listening to changes in realtime');
    }

    function failCallback(data) {
        //alert('something went wrong, check the console logs');
        console.log(data);
    }

    user1.subscribe(callback).then(successCallback, failCallback);
});




