# Task 5 — B-Tree and B+ Tree Indexing

Insertion keys: `101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113`. Each node holds at most three keys.

## Final B-Tree

```text
                         [104 | 108]
                   /         |          \
               [102]       [106]       [110]
              /    \       /    \       /     \
          [101]  [103]  [105]  [107] [109] [111|112|113]
```

### Search for Patient_ID = 110

1. Compare 110 with root `[104 | 108]`; go to the right child.
2. Arrive at internal node `[110]`.
3. Key 110 is found.

## Final B+ Tree

Internal nodes are shown in brackets; all record keys remain in linked leaf nodes.

```text
                            [107]
                       /             \
                 [103 | 105]       [109 | 111]
                /    |     \       /     |      \
             [101,102] [103,104] [105,106] [107,108] [109,110] [111,112,113]

Leaf links: [101,102] → [103,104] → [105,106] → [107,108] → [109,110] → [111,112,113]
```

### Search for Patient_ID = 108

1. At root `[107]`, 108 is greater than or equal to 107, so go right.
2. At `[109 | 111]`, 108 is less than 109, so take the left leaf.
3. Leaf `[107,108]` contains 108.

## Database indexing connection

The production table uses an indexed `patient_id` primary key automatically. The SQL schema also adds indexes for common lookups: patient name/phone, appointment date/patient, admission status, and bill patient/status. MySQL commonly implements these with B-tree-family indexes.
