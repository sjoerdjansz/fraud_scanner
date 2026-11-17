<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
</script>

<template>
    <Head title="Fraud Scanner">
        <link rel="preconnect" href="https://rsms.me/"/>
    </Head>
    <div
        class="flex h-dvh flex-col items-center bg-[#FDFDFC]  text-[#1b1b18]"
    >
        <div
            class="flex flex-col flex-1 max-w-[1024px] w-full overflow-hidden p-4 gap-4"
        >
            <header class="flex w-full flex-col bg-gray-100 p-6 rounded-md shadow-2xs gap-4">
                <div class="flex gap-4 items-center justify-between">
                    <h1 class="text-2xl">Fraud Scanner</h1>
                <button
                    class="shrink-0 cursor-pointer rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-600"
                >
                    Get Customers
                </button>
                </div>
                    <p>
                        This applications scans all customers and highlights
                        the fraudulent ones.
                    </p>
            </header>
            <main class="flex-1 overflow-auto">
                <table class="w-full border-separate border-spacing-y-2 text-left
                       [&_th]:bg-gray-800 [&_th]:text-white [&_th]:px-3 [&_th]:py-2
                       [&_td]:bg-gray-100 [&_td]:px-3 [&_td]:py-2
                       [&_tr>*:first-child]:rounded-l-sm [&_tr>*:last-child]:rounded-r-sm whitespace-nowrap">
                    <thead>
                    <tr >
                        <th>ID</th>
                        <th>Name</th>
                        <th>Date of birth</th>
                        <th>Phone</th>
                        <th>IBAN</th>
                        <th>IP</th>
                    </tr>
                    </thead>
                    <tbody class="">
                    <tr>
                        <td>1</td>
                        <td>Jan de Vries</td>
                        <td>1985-03-12</td>
                        <td>+31 6 12345678</td>
                        <td>NL91ABNA0417164300</td>
                        <td>84.241.192.17</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Sanne Bakker</td>
                        <td>1992-07-28</td>
                        <td>+31 6 87654321</td>
                        <td>NL20INGB0001234567</td>
                        <td>84.241.192.17</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Lucas Jansen</td>
                        <td>2010-11-05</td>
                        <td>+31 6 23456789</td>
                        <td>NL39RABO0300065264</td>
                        <td>145.53.12.201</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Emma Visser</td>
                        <td>1978-01-19</td>
                        <td>+49 151 23456789</td>
                        <td>NL02SNSB0912345678</td>
                        <td>213.127.88.42</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Daan Smit</td>
                        <td>2001-09-30</td>
                        <td>+31 6 34567890</td>
                        <td>NL20INGB0001234567</td>
                        <td>92.109.4.156</td>
                    </tr>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</template>
