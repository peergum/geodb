<?php

return [
    /*
     * "all" for all countries or a two letter code (e.g. US)
     * "ALL" (uppercase) to use one huge file (1.6G), with higher memory consumption (and failure risk)
     */
    'countries' => 'CA',
//    'countries' => 'US CA GB AU',

    /*
     * force new download
     */
    'update' => false,

    /*
     * number of records upserted at once... recommended: 100
     */
    'batch_size' => 100,
];
