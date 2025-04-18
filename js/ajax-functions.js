$(document).ready(function () {
  // Real-time job search
  $("#job-search-input").on("input", function () {
    const searchTerm = $(this).val();
    if (searchTerm.length >= 2) {
      $.ajax({
        url: "backend/search-jobs.php",
        method: "POST",
        data: { search: searchTerm },
        success: function (response) {
          $("#job-results").html(response);
        },
        error: function (xhr, status, error) {
          console.error("Search error:", error);
        },
      });
    }
  });

  // Job application status update
  $(".application-status").on("change", function () {
    const applicationId = $(this).data("application-id");
    const newStatus = $(this).val();

    $.ajax({
      url: "backend/update-application.php",
      method: "POST",
      data: {
        application_id: applicationId,
        status: newStatus,
      },
      success: function (response) {
        if (response.success) {
          showNotification("Status updated successfully", "success");
        } else {
          showNotification("Failed to update status", "error");
        }
      },
      error: function (xhr, status, error) {
        console.error("Update error:", error);
        showNotification("Error updating status", "error");
      },
    });
  });

  // Real-time notifications
  function checkNotifications() {
    $.ajax({
      url: "backend/check-notifications.php",
      method: "GET",
      success: function (response) {
        if (response.count > 0) {
          updateNotificationBadge(response.count);
          if (response.notifications) {
            updateNotificationList(response.notifications);
          }
        }
      },
    });
  }

  // Check notifications every 30 seconds
  setInterval(checkNotifications, 30000);

  // Job posting form submission
  $("#post-job-form").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
      url: "backend/post-job.php",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          showNotification("Job posted successfully", "success");
          $("#post-job-form")[0].reset();
        } else {
          showNotification(response.message || "Failed to post job", "error");
        }
      },
      error: function (xhr, status, error) {
        console.error("Post job error:", error);
        showNotification("Error posting job", "error");
      },
    });
  });

  // Profile update
  $("#profile-update-form").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
      url: "backend/update-profile.php",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          showNotification("Profile updated successfully", "success");
        } else {
          showNotification(
            response.message || "Failed to update profile",
            "error"
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("Profile update error:", error);
        showNotification("Error updating profile", "error");
      },
    });
  });

  // Helper function for notifications
  function showNotification(message, type) {
    const notification = $("<div>")
      .addClass("notification")
      .addClass(type)
      .text(message);

    $(".notifications-container").append(notification);

    setTimeout(function () {
      notification.fadeOut(function () {
        $(this).remove();
      });
    }, 3000);
  }

  // Helper function to update notification badge
  function updateNotificationBadge(count) {
    $(".notification-badge").text(count).show();
  }

  // Helper function to update notification list
  function updateNotificationList(notifications) {
    const list = $(".notification-list");
    list.empty();

    notifications.forEach(function (notification) {
      list.append(`
        <div class="notification-item">
          <p>${notification.message}</p>
          <small>${notification.timestamp}</small>
        </div>
      `);
    });
  }
});
