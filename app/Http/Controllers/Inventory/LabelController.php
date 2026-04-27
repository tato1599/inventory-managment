<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class LabelController extends Controller
{
    public function print(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $items = Item::whereIn('id', $ids)->get();

        $renderer = new ImageRenderer(
            new RendererStyle(120),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        return view('inventory.labels', [
            'items' => $items,
            'writer' => $writer
        ]);
    }
}
