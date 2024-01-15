/**
 * Page User List
 */

'use strict';

// Validation & Phone mask
(function() {
    // Add New Designation Form Validation
    const fv = FormValidation.formValidation(addNewDesignationForm, {
        fields: {
            title: {
                validators: {
                    notEmpty: {
                        message: 'Please enter designation title. '
                    }
                }
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                // Use this for enabling/changing valid/invalid class
                eleValidClass: '',
                rowSelector: function(field, ele) {
                    // field is the field name & ele is the field element
                    return '.mb-3';
                }
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            // Submit the form when all fields are valid
            // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
            // autoFocus: new FormValidation.plugins.AutoFocus()
        }
    });
})();