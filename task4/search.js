$(document).ready(function() {
    $('#searchinput').on('input', function() {
        var query = $(this).val();
        $.ajax({
            url: 'search_friend.php',
            method: 'POST',
            data: { search: query },
            success: function(data) {
                $('#searchresults').html(data);
            }
        });
    });
});
function sendRequest(friendId) {
  $.ajax({
    url: 'send_request.php', 
    method: 'POST',
    data: { receiver_id: friendId }, 
    success: function (response) {
      alert(response); 
      $('#friend-' + friendId).html(`<strong>Request Sent</strong> - Status: pending <button disabled>Pending</button>`); 
    },
    error: function () {
      alert('Something went wrong. Please try again.');
    }
  });
}
function handleRequest(friendId, action) {
    $.ajax({
        url: 'handle_request.php',
        method: 'POST',
        data: {
            friend_id: friendId,
            action: action
        },
        success: function(response) {
            alert(response); 
            $('#searchinput').trigger('input'); 
            if (action === 'accepted') {
        $('#friend-' + friendId).html(`<strong>Friend</strong> - <button onclick="messageFriend(${friendId})">Message</button>`);
      } else if (action === 'rejected') {
        $('#friend-' + friendId).html(`<strong>Request Rejected</strong> - <button onclick="sendRequest(${friendId})">Send Request</button>`);
      } else if (action === 'blocked') {
        $('#friend-' + friendId).hide(); 
      }
    },
        error: function(xhr) {
            alert("Error: " + xhr.responseText);
        }
    });
}
