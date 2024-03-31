<div class="container mx-auto">
<div>
    <!--
  This example requires some changes to your config:

  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ],
  }
  ```
-->
    <div>
        <label for="company-website" class="block text-sm font-medium leading-6 text-gray-900">Company Website</label>
        <div class="mt-2 flex rounded-md shadow-sm">
            <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 px-3 text-gray-500 sm:text-sm">http://</span>
            <input type="text" name="company-website" id="company-website" class="block w-full min-w-0 flex-1 rounded-none rounded-r-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="www.example.com">
        </div>
    </div>

    <div class="sm:hidden">
        <label for="tabs" class="sr-only">Select a tab</label>
        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
        <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
            <option>My Account</option>
            <option>Company</option>
            <option selected>Team Members</option>
            <option>Billing</option>
        </select>
    </div>
    <div class="hidden sm:block">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
                <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 whitespace-nowrap border-b-2 py-4 px-6 text-sm font-medium">My Account</a>
                <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 whitespace-nowrap border-b-2 py-4 px-6 text-sm font-medium">Company</a>
                <a href="#" class="border-indigo-500 text-indigo-600 whitespace-nowrap border-b-2 py-4 px-6 text-sm font-medium" aria-current="page">Team Members</a>
                <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 whitespace-nowrap border-b-2 py-4 px-6 text-sm font-medium">Billing</a>
            </nav>
        </div>
    </div>
</div>

</div>