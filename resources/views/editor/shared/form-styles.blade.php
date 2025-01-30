<style>
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    font-size: 0.875rem;
    line-height: 1.5;
    color: #1f2937;
    background-color: #fff;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    transition: all 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

.form-control.is-invalid {
    border-color: #ef4444;
}

.invalid-feedback {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #ef4444;
}

textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

select.form-control {
    padding-right: 2rem;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

.custom-checkbox {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    margin: 0.25rem 0;
    border-radius: 0.375rem;
    transition: background-color 0.15s ease-in-out;
}

.custom-checkbox:hover {
    background-color: #f3f4f6;
}

.custom-checkbox input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
    margin-right: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    transition: all 0.15s ease-in-out;
}

.custom-checkbox input[type="checkbox"]:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.custom-switch {
    position: relative;
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0;
}

.custom-switch input[type="checkbox"] {
    height: 0;
    width: 0;
    visibility: hidden;
}

.custom-switch label {
    cursor: pointer;
    padding-left: 3rem;
    position: relative;
}

.custom-switch label:before {
    content: '';
    position: absolute;
    left: 0;
    width: 2.5rem;
    height: 1.25rem;
    background-color: #d1d5db;
    border-radius: 1rem;
    transition: all 0.15s ease-in-out;
}

.custom-switch label:after {
    content: '';
    position: absolute;
    top: 0.25rem;
    left: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    background-color: white;
    border-radius: 50%;
    transition: all 0.15s ease-in-out;
}

.custom-switch input:checked + label:before {
    background-color: #3b82f6;
}

.custom-switch input:checked + label:after {
    left: 1.5rem;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.btn {
    display: inline-flex;
    align-items: center;
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.25rem;
    border-radius: 0.375rem;
    transition: all 0.15s ease-in-out;
    cursor: pointer;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
    border: 1px solid transparent;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    background-color: white;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
}
</style>
