(function ($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   */

  $(document).ready(function () {
    /**
     * Test Connection Button Click
     */
    $("#minniyo-test-connection").on("click", function (e) {
      e.preventDefault();

      const $button = $(this);
      const apiKey = $("#minnch_api_key").val().trim();

      // Validate API Key
      if (!apiKey) {
        showMessage(
          "Please enter your Integration API Key before testing the connection.",
          "error"
        );
        return;
      }

      // Set loading state
      $button.prop("disabled", true).addClass("loading");
      $button.find(".dashicons").addClass("dashicons-update-alt");

      // Test connection via AJAX
      $.ajax({
        url: ajaxurl,
        type: "POST",
        data: {
          action: "minniyo_test_connection",
          api_key: apiKey,
          nonce: minnch_ajax.nonce,
        },
        success: function (response) {
          if (response.success) {
            showMessage(response.data.message, "success");

            // Update connection status display
            updateConnectionStatus(true);

            // Enable the chatbot if not already enabled
            if (!$("#minnch_enabled").is(":checked")) {
              $("#minnch_enabled").prop("checked", true);
            }
          } else {
            showMessage(response.data.message, "error");

            // Show troubleshooting tips
            if (response.data.tips) {
              showTroubleshootingTips(response.data.tips);
            }

            updateConnectionStatus(false);
          }
        },
        error: function () {
          showMessage(
            "An unexpected error occurred. Please try again.",
            "error"
          );
          updateConnectionStatus(false);
        },
        complete: function () {
          // Remove loading state
          $button.prop("disabled", false).removeClass("loading");
        },
      });
    });

    /**
     * Save Settings Button Click
     */
    $("#minniyo-save-settings").on("click", function (e) {
      e.preventDefault();

      const $button = $(this);
      const apiKey = $("#minnch_api_key").val().trim();
      const chatbotEnabled = $("#minnch_enabled").is(":checked") ? "1" : "0";

      // Validate API Key
      if (!apiKey) {
        showMessage(
          "Please enter your Integration API Key before saving.",
          "error"
        );
        return;
      }

      // Set loading state
      $button.prop("disabled", true).addClass("loading");

      // Save settings via AJAX
      $.ajax({
        url: ajaxurl,
        type: "POST",
        data: {
          action: "minniyo_save_settings",
          api_key: apiKey,
          chatbot_enabled: chatbotEnabled,
          nonce: minnch_ajax.nonce,
        },
        success: function (response) {
          if (response.success) {
            showMessage(response.data.message, "success");
          } else {
            showMessage(response.data.message, "error");
          }
        },
        error: function () {
          showMessage(
            "An unexpected error occurred. Please try again.",
            "error"
          );
        },
        complete: function () {
          // Remove loading state
          $button.prop("disabled", false).removeClass("loading");
        },
      });
    });

    /**
     * Show Message
     */
    function showMessage(message, type) {
      const $messageBox = $("#minniyo-message");

      $messageBox
        .removeClass("success error warning")
        .addClass(type)
        .html(message)
        .slideDown(300);

      // Auto hide after 5 seconds
      setTimeout(function () {
        $messageBox.slideUp(300);
      }, 5000);
    }

    /**
     * Update Connection Status Display
     */
    function updateConnectionStatus(isSuccess) {
      const $statusDiv = $(".minniyo-connection-status");

      if ($statusDiv.length) {
        $statusDiv
          .removeClass("status-success status-error")
          .addClass(isSuccess ? "status-success" : "status-error");

        const icon = isSuccess
          ? '<span class="dashicons dashicons-yes-alt"></span>'
          : '<span class="dashicons dashicons-warning"></span>';

        const text = isSuccess
          ? "Connection verified successfully!"
          : "Connection could not be verified.";

        $statusDiv.html(icon + "<span>" + text + "</span>");
      } else {
        // Create status div if it doesn't exist
        const statusClass = isSuccess ? "status-success" : "status-error";
        const icon = isSuccess
          ? '<span class="dashicons dashicons-yes-alt"></span>'
          : '<span class="dashicons dashicons-warning"></span>';

        const text = isSuccess
          ? "Connection verified successfully!"
          : "Connection could not be verified.";

        const $newStatus = $(
          '<div class="minniyo-connection-status ' +
            statusClass +
            '">' +
            icon +
            "<span>" +
            text +
            "</span></div>"
        );

        $newStatus.insertBefore(".minniyo-form-actions");
      }
    }

    /**
     * Show Troubleshooting Tips
     */
    function showTroubleshootingTips(tips) {
      let tipsHtml =
        "<strong>Troubleshooting Tips:</strong><ul style='margin: 10px 0 0; padding-left: 20px;'>";

      tips.forEach(function (tip) {
        tipsHtml += "<li>" + tip + "</li>";
      });

      tipsHtml += "</ul>";

      setTimeout(function () {
        showMessage(tipsHtml, "warning");
      }, 500);
    }

    /**
     * Toggle effect for enable/disable
     */
    $("#minnch_enabled").on("change", function () {
      const isEnabled = $(this).is(":checked");

      if (isEnabled) {
        const apiKey = $("#minnch_api_key").val().trim();

        if (!apiKey) {
          showMessage(
            "Please enter your Integration API Key before enabling the chatbot.",
            "warning"
          );
          $(this).prop("checked", false);
        }
      }
    });
  });
})(jQuery);
