<script>
    (() => {
        const form = document.currentScript.closest('form');
        if (!form) return;

        const initialize = () => {
            const phones = form.querySelectorAll('[name="phone_number"], [name="parent_phone_number"]');
            const nrc = form.querySelector('[name="nrc"]');
            const dob = form.querySelector('[name="date_of_birth"]');
            const submitButtons = form.querySelectorAll('[type="submit"]');

            const validate = () => {
                phones.forEach(phone => {
                    phone.setCustomValidity(phone.value && !/^[0-9]{10}$/.test(phone.value)
                        ? 'Phone number must contain exactly 10 digits.' : '');
                });
                if (nrc) {
                    nrc.setCustomValidity(nrc.value && !/^[0-9]{1,6}\/[0-9]{2}\/[0-9]$/.test(nrc.value)
                        ? 'NRC must be at most 11 characters, in the format 123456/78/9.' : '');
                }
                if (dob) {
                    const today = new Date();
                    const localToday = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
                    dob.setCustomValidity(dob.value && dob.value >= localToday
                        ? 'Date of birth must be in the past.' : '');
                }
                const valid = Array.from(form.elements).every(field => {
                    if (!field.willValidate) return true;
                    return field.validity.valid && (!field.required || field.value.trim() !== '');
                });
                submitButtons.forEach(button => button.disabled = !valid);
                return valid;
            };

            form.addEventListener('input', event => {
                const field = event.target;
                if (field.matches('[name="phone_number"], [name="parent_phone_number"]')) {
                    field.value = field.value.replace(/[^0-9]/g, '').slice(0, 10);
                } else if (field === nrc) {
                    field.value = field.value.replace(/[^0-9/]/g, '').slice(0, 11);
                }
                validate();
            });
            form.addEventListener('change', validate);
            form.addEventListener('reset', () => setTimeout(validate, 0));
            form.addEventListener('submit', event => {
                if (!validate()) {
                    event.preventDefault();
                    form.reportValidity();
                }
            });
            validate();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initialize, { once: true });
        } else {
            initialize();
        }
    })();
</script>
