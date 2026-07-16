<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ImageuploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.pages.index');
    }

    public function data(Request $request)
    {
        $query = Page::query();

        return DataTables::eloquent($query)
            ->addColumn('action', function (Page $page): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.pages.edit', ['page' => $page->id]) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5">Edit</a>'
                    . '<form method="POST" action="' . route('admin.pages.destroy', ['page' => $page->id]) . '" class="inline-block" data-confirm="Delete this page?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-600 dark:bg-red-500/10 dark:text-red-200 dark:hover:bg-red-500/20">Delete</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create(Request $request)
    {
        if ($request->ajax() && $request->has('get_section_block')) {
            $title = $request->input('title', 'Custom Section');
            $index = $request->input('index');
            $fields = $request->input('fields', []);
            
            return view('admin.pages.pages.partials.section-block-dynamic', [
                'title' => $title,
                'index' => $index,
                'fields' => $fields,
                'section' => []
            ]);
        }
        return view('admin.pages.pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
        ]);

        $page = new Page();
        $page->title = $request->input('title');
        $page->slug = Str::slug($request->input('slug'));
        $page->content = $request->input('content');

        $page->metadata = ['sections' => $this->parseSections($request)];
        $page->save();

        return redirect()
            ->route('admin.pages')
            ->with('status', "Page '{$page->title}' created successfully.");
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
        ]);

        $page->title = $request->input('title');
        $page->slug = Str::slug($request->input('slug'));
        $page->content = $request->input('content');

        $page->metadata = ['sections' => $this->parseSections($request)];
        $page->save();

        return redirect()
            ->route('admin.pages')
            ->with('status', "Page '{$page->title}' updated successfully.");
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        return redirect()
            ->route('admin.pages')
            ->with('status', "Page deleted successfully.");
    }

    private function parseSections(Request $request): array
    {
        $sectionsInput = $request->input('sections', []);
        $sections = [];

        foreach ($sectionsInput as $secIndex => $data) {
            $section = [
                'title' => $data['title'] ?? '',
                'fields' => [],
            ];

            if (isset($data['fields']) && is_array($data['fields'])) {
                foreach ($data['fields'] as $fieldIndex => $field) {
                    $type = $field['type'] ?? 'text';
                    $label = $field['label'] ?? '';
                    $name = $field['name'] ?? Str::slug($label);

                    $fieldData = [
                        'label' => $label,
                        'name' => $name,
                        'type' => $type,
                    ];

                    if ($type === 'image') {
                        if ($request->hasFile("sections.{$secIndex}.fields.{$fieldIndex}.image")) {
                            $fieldData['value'] = ImageuploadHelper::uploadImage(
                                $request->file("sections.{$secIndex}.fields.{$fieldIndex}.image"),
                                'pages'
                            );
                        } else {
                            $fieldData['value'] = $field['image_url'] ?? ($field['image_current'] ?? '');
                        }
                    } else {
                        $fieldData['value'] = $field['value'] ?? '';
                    }

                    $section['fields'][] = $fieldData;
                }
            }

            $sections[] = $section;
        }

        return $sections;
    }
}
