jQuery(document).ready(function ($) {
  $("#post").submit(function (event) {
    let isValid = true;

    let authorName = $('input[name="author_name"]').val();
    if (!authorName) {
      isValid = false;
      $('input[name="author_name"]').addClass("input-error");
      if ($("#author-name-error").length === 0) {
        $('input[name="author_name"]').after(
          '<span id="author-name-error" class="error-message">Author name is required.</span>'
        );
      }
    } else {
      $('input[name="author_name"]').removeClass("input-error");
      $("#author-name-error").remove();
    }

    let publicationDate = $('input[name="publication_date"]').val();
    if (!publicationDate) {
      isValid = false;
      $('input[name="publication_date"]').addClass("input-error");
      if ($("#publication-date-error").length === 0) {
        $('input[name="publication_date"]').after(
          '<span id="publication-date-error" class="error-message">Publication date is required.</span>'
        );
      }
    } else {
      $('input[name="publication_date"]').removeClass("input-error");
      $("#publication-date-error").remove();
    }

    if ($('input[name="subject_category[]"]:checked').length === 0) {
      isValid = false;
      $("ul.categorychecklist").addClass("input-error");
      if ($("#subject-category-error").length === 0) {
        $("ul.categorychecklist").after(
          '<span id="subject-category-error" class="error-message">At least one subject category is required.</span>'
        );
      }
    } else {
      $("ul.categorychecklist").removeClass("input-error");
      $("#subject-category-error").remove();
    }

    if (!isValid) {
      event.preventDefault();
    }
  });

  $(".add-new-subject a").click(function (e) {
    e.preventDefault();
    $(".new-subject-form").slideToggle();
  });

  $("#add-new-subject").click(function (e) {
    e.preventDefault();

    var title = $("#new-subject-title").val();
    var parent = $("#new-subject-parent").val();

    if (title === "") {
      alert("Please enter a subject title.");
      return;
    }

    $.ajax({
      method: "POST",
      url: ajaxurl,
      data: {
        action: "tutopiya_add_new_subject",
        title: title,
        parent: parent,
        nonce: $("#tutopiya_nonce").val(),
      },
      success: function (response) {
        if (response.success) {
          location.reload();
        } else {
          alert(response.data);
        }
      },
    });
  });
});
