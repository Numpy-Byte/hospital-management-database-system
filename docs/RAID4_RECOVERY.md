# Task 3 — RAID Level 4 Recovery Mechanism

## Disk layout

| Disk | D1 | D2 | D3 | D4 | D5 | D6 | P |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Data | Patient | Doctor | Appointment | Room | Treatment | Billing | Dedicated parity |
| 4-bit value | 1010 | 1100 | 0111 | 1001 | 0011 | 1110 | 0101 |

RAID 4 uses block-level striping over D1–D6 and keeps one dedicated parity block P. XOR has the property that `A XOR A = 0000`; therefore a missing block can be reconstructed with parity and all remaining blocks.

## Parity calculation

```text
D1  1010
XOR D2  1100  = 0110
XOR D3  0111  = 0001
XOR D4  1001  = 1000
XOR D5  0011  = 1011
XOR D6  1110  = 0101

Parity P = 0101
```

## Recover failed D5 (Treatment Data)

```text
D5 = P XOR D1 XOR D2 XOR D3 XOR D4 XOR D6
   = 0101 XOR 1010 XOR 1100 XOR 0111 XOR 1001 XOR 1110
   = 0011
```

The recovered value is `0011`, which matches the original D5 Treatment Data block. RAID 4 can recover from one disk failure because XOR parity contains the information needed to solve for exactly one unknown data block. It cannot recover if two data disks fail before recovery is completed.
